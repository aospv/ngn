<?php

class GrabberSourceHtmlDated extends GrabberSourceHtmlAbstract {
  
  static public $title = 'HTML с датой';

  /**
   * @var Curl
   */
  protected $oCurl;
  
  protected function init() {
    $this->oCurl = new Curl();
    if (empty($this->channelData['urlN']))
      $this->channelData['urlN'] = 0;
    parent::init();
  }

  protected function _getChannelItems() {
    $info = $this->channelData;
    
    $content = $this->oCurl->get($info['url']);
    $content = $this->oCurl->getParsed($content, $info['itemsBegin'], $info['itemsEnd']);
    if (!$content)
      throw new NgnException(
        'No content received. '.
        'Begin: '.htmlspecialchars($info['itemsBegin']).', '.
        'End: '.htmlspecialchars($info['itemsEnd'])
      , 1014);
    
    preg_match_all('/'.Misc::str2regexp($info['itemContentBegin']).'(.*?)'.str2regexp($info['itemContentEnd']).'/si', $content, $m);
    
    if (empty($m[1])) {
      $this->error = 'Куски начала <b>'.htmlspecialchars($info['itemContentBegin']).
      '</b> или конца <b>'.htmlspecialchars($info['itemContentEnd']).
      '</b> записи не найдены.
      <ul>
        <li>Возможно, вы выбрали участки начали и конца контента, 
        которые встречаются в других местах документа.</li>
        <li>Или, возможно, список записей выл выбран неверно</li>
      </ul>
      <b>Текст списка записей:</b>
      <hr />'.htmlspecialchars($content);
      return false;
    }
    
    if (!empty($info['pageTitleDelimiter']))
      $ptd = str_replace('/', '\\/', $info['pageTitleDelimiter']);
    
    foreach ($m[1] as $n => $content) {
      if ($info['garbage']) {
        foreach ($info['garbage'] as $g) {
          if (empty($g['end'])) {
            $content = $this->clearContent($content, $g['begin']);
          } else
            $content = $this->getClearedContent($content, $g['begin'], $g['end']);
        }
      }
      
      if ($info['dateMode'] == 'item') {
        // Парсим дату
        $date = Html::getInnerContent($content, $info['dateTagBegin'], $info['dateTagEnd']);
      }
      
      // Парсим ссылку с заголовком
      preg_match_all('/<a.*href=["\'](.*)["\'][^>]*>(.*)<\/a>/siU', $content, $m);
      
      if (!$m[1][0]) {
        // шаблон для параметров href без кавычек
        preg_match_all('/<a.*href=([^\s]+)(?: [^>]*)?>(.*)<\/a>/siU', $content, $m);
        if (!$m[1][0]) {
          $this->error = 'Ссылка пустая. Контент <b>'.htmlspecialchars($content).'</b>';
          return false;
        }
      }
      
      if (!isset($m[0][$info['urlN']])) {
        $this->error = 'Ссылка №'.($info['urlN']+1).' не существует.
        Всего в записи ссылок '.count($m[0]).': '.
        implode(', ', array_map('externalLinkTag', $m[1])).
          '<hr>'.$info['urlN'];
        return false;
      }
      
      $content = preg_replace('/<a .*<\/a>/', '', $content);
      $link = $m[1][$info['urlN']];
      $u1 = parse_url($info['url']);
      $u2 = parse_url($link);
      $link = $u1['scheme'].'://'.$u1['host'].$u2['path'].
        (isset($u2['query']) ? '?'.$u2['query'] : '');
      
      $oCurl = new Curl();
      if (!($oHttpResult = $oCurl->getObj($link)))
        throw new NgnException('Can not load url "'.$link.'"', 1015);
        
      if ($info['titleMode'] == 'page') {
        $title = Html::getInnerContent($oHttpResult->getBody(), '<title>', '</title>');
        if (isset($ptd)) {
          if ($info['pageTitleFormat'] == 1) {
            $regexp = '/([^'.$ptd.']*)'.$ptd.'.*/';
          } else {
            $regexp = '/.*'.$ptd.'([^'.$ptd.']*)/';
          }
          $title = preg_replace($regexp, '$1', $title);
          $title = trim($title);
        }        
      } else {
        $title = $m[2][$info['urlN']];
        $title = preg_replace('/<noindex>.*<\/noindex>/si', '', $title);
        $title = strip_tags($title);
        
        if (!trim($title)) {
          throw new NgnException('$title is empty. mode: '.$info['titleMode'].'. '.
            count($m[0] == 1) ?
              'Смените тип на "page"' :
              'Имеющиеся в записи ссылки: '.ol(array_map('htmlspecialchars', $m[0])),
            1016
          );
        }
      }
      
      try {
        $content = $this->_getHTMLContent($oHttpResult, array(
          'contentBegin' => $info['contentBegin'],
          'contentEnd' => $info['contentEnd'],
          'garbage' => $info['garbage2'],
        ));
      } catch (NgnException $e) {
        if ($e->getCode() == self::ERR_EMPTY_CONTENT) {
          $s = "<h2>Запись №".($n+1)." Не найден текст по ссылке ".externalLinkTag($link)."</h2>
            Возможные проблемы:<ul>
            <li>Страница записи ".externalLinkTag($link)." не содержит текста начала <b>
            ".htmlspecialchars($info['contentBegin'])."
            </b> или конца <b>".htmlspecialchars($info['contentEnd'])."</b> контента </li>
            <li>Запись Страница ".externalLinkTag($link)." не содержит текста начала <b>
            ".htmlspecialchars($info['contentBegin'])."
            </b> или конца <b>".htmlspecialchars($info['contentEnd'])."</b> контента </li>
            </ul><hr>".htmlspecialchars($oHttpResult->getBody());
        } elseif ($e->getCode() == self::ERR_OUTSIDE_SERVER) {
          $s = "<h2>Запись находится на стороннем сервере (".externalLinkTag($link).")</h2>
          Это может означать, что данный ресурс является аргрегатором для других сайтов 
          и не может быть использован для парсинга контента.
          ";
        } else $s = $e->getMessage();
        $this->error = $s;
        return false;
      }
      
      if (!$content) {
        $this->error = 'Content is empty';
        return false;
      }
            
      if ($info['dateMode'] == 'page') {
        $initContent = $oHttpResult->getBody();
        $date = Html::getInnerContent($initContent, $info['dateTagBegin'], $info['dateTagEnd']);
        if (!$date)
          throw new NgnException(
            'Дата не найдена на странице. Куски: '.
              '«<b>'.htmlspecialchars($info['dateTagBegin']).'</b>», '.
              '«<b>'.htmlspecialchars($info['dateTagEnd']).'</b>». Контент:<hr>'.
              htmlspecialchars($initContent),
            1017
          );
        // -----------------
        $content = preg_replace(
          '/'.str_replace('/', '\\/', $info['dateTagBegin']).'.*'.str_replace('/', '\\/', $info['dateTagEnd']).'/si', '', $content);
      }
      
      if (!$date) {
        $this->error = 'Ошибка парсинга даты. mode: '.$info['dateMode'].'. Куски: '.
          '«<b>'.htmlspecialchars($info['dateTagBegin']).'</b>», '.
          '«<b>'.htmlspecialchars($info['dateTagEnd']).'</b>». Контент:<hr>'.
          htmlspecialchars($content);
        return false;
      }
      // Очищаем дату от всякого говна
      $date = trim(strip_tags($date));
      if (strstr($info['dateFormat'], 'month')) {
        // для подобного: Пятница 10 сентября 2010  года
        $date = preg_replace('/\s+/', ' ', str_replace('года', '', $date));
      }
      $date = dateParse($date, $info['dateFormat'], 'd.m.Y H:i:s');
      
      $items[] = array(
        'title' => $title,
        'text' => $content,
        'link' => $link,
        'dateCreate' => $date,
        'dateCreate' => $date
      );
      if (($n+1) == $this->itemsLimit) break;
    }
    return $items;
  }
  
}
