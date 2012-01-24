<?php

class TestGrabberSourceHtmlDated extends TestGrabber {
  
  protected $module = 'news';
  protected $channelId;
  
  protected function beforeRun() {
    parent::beforeRun();
    $this->channelId = db()->query('INSERT INTO grabberChannel SET ?a', array(
      'type' => 'htmlDated',
      'data' => serialize(array(
        'itemsBegin' => 'sdqwdqd',
        'itemsEnd' => '1r23fr23',
        //'itemContentBegin' => '123',
        //'itemContentEnd' => '333',
        'dateMode' => 'page',
        'dateTagBegin' => '<b class="date">',
        'dateTagEnd' => '</b>',
        'dateFormat' => 'd.m.Y',
        'titleMode' => 'link'
      )),
      'dateCreate' => dbCurTime(),
      'pageId' => $this->pageId
    ));
  }

}
