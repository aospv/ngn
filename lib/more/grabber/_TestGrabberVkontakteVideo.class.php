<?php

class TestGrabberVkontakteVideo extends TestGrabber {
  
  protected $module = 'video';
  
  /**
   * @var GrabberSourceVkontakteVideo
   */
  protected $oG;
  
  /**
   * @var GrabberDdImporter
   */
  protected $oGI;
  
  protected function beforeRun() {
    parent::beforeRun();
    $this->createChannel(array(
      'type' => 'vkontakteVideo',
      'pageId' => $this->pageId,
      'data' => serialize(array(
        'title' => 'vkontakte video',
        //'url' => 'http://vkontakte.ru/video.php?gid=21812717' // mine
        'url' => 'http://vkontakte.ru/video.php?gid=7209770' // nikemat
        //'url' => 'http://vkontakte.ru/video.php?gid=133594' // bloodymilk
        //'url' => 'http://vkontakte.ru/video.php?gid=10107454' // НЕТ язычеству на Святой Руси
      ))
    ));
    $this->oG = new GrabberSourceVkontakteVideo($this->channelId);
    $this->oGI = new GrabberDdImporter($this->oG);
  } 
  
  protected $n;
  
  public function test_saveListPageItemsAll() {
    $this->oG->saveListPageItemsAll();
    $d = $this->oG->getSavedListPageItems();
    $this->n = count($d);
    foreach ($d as $k => $v)
      if (empty($v['timeCreate']))
        throw new NgnException("'tiemCreate' #$k is empty. \$v=".getPrr($v), 1021);
    $this->assertTrue($this->oG->getItemsCount() == count($d));
  }
  
  public function test_importAll() {
    $ids = $this->oGI->importAllSaved();
    $this->assertTrue(count($ids) == $this->n, 'all dd-items created');
    foreach ($ids as $id) {
      $folder = UPLOAD_PATH.'/dd/'.$this->oGI->manager->strName.'/'.$id;
      $this->assertTrue(file_exists($folder.'/video.jpg'), 'video thumb exists');
      $this->assertTrue((
        file_exists($folder.'/video.flv') or
        file_exists($folder.'/video.mpg')
      ), 'converted video exists');
    }
  }
  
}
