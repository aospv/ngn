<?php

class VkVideoGrabber extends VkAuth {
  
  protected $videoListUrl;
  public $step = 50;
  public $limit = 0;
  
  /**
   * @var Wget
   */
  protected $oWget;
  
  /**
   * Определяет ссылку страницы со списком видео
   *
   * @param   string  Ссылка на страницу. Пример: "/video.php?gid=133594"
   */  
  public function setListLink($link) {
    $this->videoListUrl = $this->baseUrl.'/'.Misc::clearFirstSlash($link);
  }
  
  public function init() {
    $this->getData();
    Dir::make($this->dataFolder.'/temp');
    $this->oWget = new Wget(array('tempFolder' => $this->dataFolder.'/temp'));
  }
  
  public function grab($st = 0) {
    if (!$this->auth()) return false;
    $grabbedItems = array();
    while (($items = $this->getListPageItems($st))) {
      foreach ($items as $item) {
        $n++;
        if (($grabbedItem = $this->processVideoPage($item))) {
          $grabbedItems[] = $grabbedItem;
        } else {
          throw new NgnException('Grab item error. Item: '.getPrr($item), 1028);
        }
        if ($this->limit and $n == $this->limit) {
          return $grabbedItems;
        }
      }
      $st += $this->step;
    }
    return $grabbedItems;
  }
  
  public function updateData($st = 0) {
    if (!$this->auth()) return;
    $n = 0;
    while (($items = $this->getListPageItems($st))) {
      foreach ($items as $item) {
        $n++;
        $this->processVideoPage($item, true);
        if ($this->limit and $n == $this->limit) return;
      }
      $st += $this->step;
    }
  }
  
  public function showPagesRange() {
    if (!$this->auth()) return;
    $st = 0;
    while ($this->parseListPage($st, true)) {
      $st += $this->step;
    }
    output('Total range: 0-'.($st-$this->step));
  }
  
  public function getListPageItems($st = 0) {
    if (!$this->auth()) return false;
    if (!isset($this->videoListUrl))
      throw new NgnException('$this->videoListUrl not defined', 1029);
    output("Parsing page st=$st");
    $url = $this->videoListUrl.($st ? '&st='.$st : '');
    output('Get page from "'.$url.'"');
    $c = $this->oCurl->get($url);
    if (!$c) throw new NgnException("Content by url '$url' is empty", 1030);
    
    // parse links
    preg_match_all('/href="(video-[^"]+)"/', $c, $m);
    if (empty($m[1])) {
      throw new NgnException('Video links not found. Maby not log in or too fast requests?', 311);
      //output('Video links not found. Maby not log in or too fast requests?');
      //return false;
    }
    $links = $m[1];
    $links = Arr::str_replace($links, '#comments', '');
    $links = array_values(array_unique($links));
    
    // parse date create
    preg_match_all('/<div class="video_created">([^<]*)<span/s', $c, $m);
    $times = $m[1];
    foreach ($times as &$v)
      $v = dateParse($v, 'd ru-month Y', 'timestamp');
      
    $items = array();
    foreach ($links as $k => $link) {
      $items[$k]['link'] = $link;
      $items[$k]['timeCreate'] = $times[$k];
    }
    output(count($items)." items parsed");
    return $items;
  }
  
  protected $data;
  
  public function getData() {
    if (isset($this->data))
      return $this->data;
    $dataFile = $this->dataFolder.'/data.dat';
    if (file_exists($dataFile)) {
      $data = file_get_contents($dataFile);
      $data = unserialize($data);
    } else {
      $data = array();
    }
    $this->data = $data;
    return $data;
  }
  
  public function saveData($data) {
    $dataFile = $this->dataFolder.'/data.dat';
    file_put_contents($dataFile, serialize($data));    
    $this->data = $data;
  }
  
  protected $totalCount;
  
  public function getTotalCount() {
    $this->auth(true);
    if (isset($this->totalCount)) return $this->totalCount;
    if (!($c = $this->oCurl->get($this->videoListUrl)))
      throw new NgnException('no content by url "'.$this->videoListUrl.'"', 1031);
    preg_match('/<b>(\d+) видеозапис/s', $c, $m);
    if (empty($m[1])) {
      throw new NgnException("Wrong video list page by url '{$this->videoListUrl}'", 1032);
    }
    return $this->totalCount = $m[1];
  }
  
  protected $hd = array(
    0 => 240,
    1 => 360,
    2 => 480,
    3 => 720,
  );
  
  public $forceDownload = false;
  
  /**
   * Определяет ссылку страницы со списком видео
   *
   * @param   string  Пример:
   *                  array(
   *                    'link' => 'video-12312312',
   *                    'timeCreate' => '132123123'
   *                  )
   * @param   bool    Только лиш обновлять данные без скачивания файлов
   */  
  public function processVideoPage(array $item, $reflashData = false) {
    if (!$this->auth()) return false;
    //die2($item['link']);
    if (empty($item['link']))
      throw new NgnException("\$item['link'] is empty", 1033);
    $url = $this->baseUrl.'/al_video.php?act=show&al=1&video='.str_replace('video', '', $item['link']);
    
    if (!($c = $this->oCurl->get($url)))
      throw new NgnException('no content', 1034);
    
    // parse vars
    // 2 попытки
    // 1-я
    if (!preg_match('/var vars = ({.*})/', $c, $m)) {
      sleep(10);
      $c = $this->oCurl->get($url);
      prr(curl_getinfo($this->oCurl->fSocket));
      // 2-я
      if (!preg_match('/var vars = ({.*})/', $c, $m)) {
        sleep(10);
        $c = $this->oCurl->get($url);
        if (!preg_match('/var vars = ({.*})/', $c, $m)) {
          LogWriter::str('url', 'url: '.$url."\n=============\n".$c);
          throw new NgnException('String "var vars = {..." not found. Contents saved to log "url"', 1035);
        }
      }
    }
    
    //$json = preg_replace('/({.*}).*/', '$1', $m[1]);
    $json = $m[1];
    $d = json_decode($json);
    
    //LogWriter::v('VVG_JSON', $d);
    
    // parse video caption
    //if (!strstr($c, 'id="videocaption"')) {
      //throw new NgnException('Caption not found');
   // }
    if (strstr($c, 'id="videocaption"')) {
      $caption = preg_replace(
        '/.*<div id="videocaption">(.*)<div style="margin-top:10px">.*/s', '$1', $c);
    } else $caption = '';
    if (!$caption)
      output("{$item['link']} caption is empty");
    
    $videoFile = $this->getFileName($d);
    $itemExists = $this->itemExists($d);
    $itemKey = $this->getItemKey($d);
    
    if (!$reflashData) {
      // Если не нужно только лишь обновить данные
      if (file_exists($videoFile)) {
        output('File from link "'.$item['link'].'", itemKey: '.$itemKey.' already exists');
      } else {
        /*
        if (!strstr($d->host, 'http://')) {
          $videoUrl = 'http://'.$d->host.'/assets/videos/'.$d->vtag.$d->vid.'.vk.flv';
        } else {
          $videoUrl = $d->host.'u'.$d->uid.'/video/'.$d->vtag.'.'.$this->hd[$d->hd].'.mp4';
        }
        */
        $host = strstr($d->host, 'http://') ? $d->host : 'http://'.$d->host.'/';
        
        //output(getPrr($d));
        
        $videoUrl1 = $host.'u'.$d->uid.'/video/'.$d->vtag.'.'.$this->hd[$d->hd].'.mp4';
        $videoUrl2 = $host.'assets/videos/'.$d->vtag.$d->vid.'.vk.flv';
        $videoUrl3 = $host.'u'.$d->uid.'/video/'.$d->vtag.'.flv';
        
        //output('Check URL existance');
        
        if ($this->oWget->exists($videoUrl1))
          $videoUrl = $videoUrl1;
        elseif ($this->oWget->exists($videoUrl2))
          $videoUrl = $videoUrl2;
        elseif ($this->oWget->exists($videoUrl3))
          $videoUrl = $videoUrl3;
        else
          throw new NgnValidError(
            'video "'.$item['link'].'" not found by urls "'.$videoUrl1.'", "'.$videoUrl2.'", "'.$videoUrl3.'"');
        
        
        if (!$this->forceDownload) {
          // 3 варианта скачки
          if (getOS() == 'win') $videoUrl = 'http://localhost/ngn/env/temp/1.mov';
          output('Download file "'.$videoUrl.'" to "'.$videoFile.'", itemKey: '.$itemKey);
          $this->oWget->download($videoUrl, $videoFile);
          //file_put_contents($videoFile, $this->oCurl->get($videoUrl));
          //copy($videoUrl, $videoFile);
          output('Download complete.');
          
          if (filesize($videoFile) == 0) {
            output('Filesize = 0. file deleted');
            unlink($videoFile);
            return false;
          }
          output('Filesize: '.filesize($videoFile).' ('.$videoFile.')');
          //if (filesize($videoFile) < 350000) {
            //unlink($videoFile);
            //throw new NgnException('Too small video file (< 350 Kb)', 1037);
          //}
        }
      }
    } else {
      // Если мы проводим лишь апдейт данных
      if (!$itemExists) {
        // И такой записи ещё не существовало, сообщаем об этом
        output('Item with key "'.$itemKey.'" not found in data');
        return false;
      } else {
        // Если она есть но нет файла к ней
        if (!file_exists($videoFile)) output("File '$itemKey' does not exists");          
        output('update data for itemKey "'.$itemKey.'"');
      }      
    }
    
    $d = get_object_vars($d);
    $d['caption'] = $caption;
    $d['link'] = $item['link'];
    $d['file'] = $videoFile;
    if (!empty($item['timeCreate']))
      $d['timeCreate'] = $item['timeCreate'];
    
    $this->saveData(array_merge(
      $this->getData(),
      array($itemKey => $d
    )));
    
    return $d;
  }
  
  protected function getFileName($d) {
    return $this->dataFolder.'/'.$this->getItemKey($d).'.mp4';
  } 
  
  protected function getItemKey($d) {
    return $d->uid.'-'.$d->vid;
  }
  
  protected function itemExists($d) {
    return isset($this->data[$this->getItemKey($d)]);
  }
  
  public function cleanupFiles() {
    $data = $this->getData();
    output("Total items: ".count($data));
    $n = 0;
    foreach ($data as $v) {
      if (!file_exists($this->dataFolder.'/'.$v['uid'].'-'.$v['vid'].'.mp4')) {
        $n++;
      }
    }
    output("Files to remove: ".$n);
  }
    
}
