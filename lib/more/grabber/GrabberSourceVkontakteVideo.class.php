<?php

class GrabberSourceVkontakteVideo extends GrabberSourceAbstract {

  static public $title = 'Вконтакте - видео';

  public $unknownTotalCount = false;
  
  /**
   * @var VkontakteVideoGrabber
   */
  public $oVVG;
  
  protected function init() {
    // -------- init vkontakte grabber --------
    $this->oVVG = new VkontakteVideoGrabber(
      Config::getVarVar('grabberVkontakte', 'email'),
      Config::getVarVar('grabberVkontakte', 'pass'),
      DATA_PATH.'/vk-video'
    );
    $this->itemsPerPage = $this->oVVG->step;
    $this->oVVG->limit = $this->limit;
    $this->oVVG->setListLink(Misc::stripHost($this->channelData['url']));
    parent::init();
  }
  
  public function downloadDdItemByListPageItem(array $listPageItem) {
    if (empty($listPageItem['link']))
      throw new NgnException("\$listPageItem['link'] is empty", 1018);
    if (!($contentItem = $this->downloadContentItemByLink($listPageItem['link'])))
      return false;
    if (empty($contentItem['file']))
      throw new NgnException("\$contentItem['file'] is empty", 1019);
    return array(
      'title' => urldecode($contentItem['md_title']),
      'text' => $contentItem['caption'],
      'link' => $contentItem['link'],
      'video' => array(
        'tmp_name' => $contentItem['file']
      ),
      'dateCreate' => dbCurTime($listPageItem['timeCreate'])
    );
  }
  
  protected function _getListPageItems($page) {
    // первая страница тут должна быть последней (начинаем парсинг с конца)
    $count = $this->getItemsCount();
    $lastPageStep = (ceil($count/$this->oVVG->step)*$this->oVVG->step)-$this->oVVG->step;
    if ($lastPageStep == 0 and $page != 0) return array();
    $st = $lastPageStep ? $lastPageStep - $page*$this->oVVG->step : $page;
    if ($st < 0) return array();
    return $this->oVVG->getListPageItems($st);
  }
    
  public function getItemsCount() {
    return $this->oVVG->getTotalCount();
  }
  
  public function getPagesCount() {
    return ceil($this->getItemsCount() / $this->itemsPerPage);    
  }

  protected function downloadContentItemByLink($link) {
    try {
      $r = $this->oVVG->processVideoPage(
        array('link' => $link)
      );
    } catch (NgnValidError $e) {
      output('processVideoPage error: '.Misc::translate($e->getMessage()));
      return false;
    }
    return $r;
  }
  
  public function getTestDdItems() {
    $this->oVVG->forceDownload = true;
    $items = parent::getTestDdItems();
    $r = array();
    foreach ($items as $item) {
      $v = $this->oVVG->processVideoPage($item);
      $r[] = array(
        'title' => $v['md_title'],
        'link' => 'http://vkontakte',
        'text' => VkontakteVideo::getCode($v)
      );
    }
    return $r;
  }
  
}
