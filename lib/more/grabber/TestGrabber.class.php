<?php

class TestGrabber {

  protected $channelId;

  protected function createChannel(array $data) {
    $this->channelId = db()->query(
      'INSERT INTO grabberChannel SET ?a', $data);
  }
  
  protected function afterRun() {
    parent::afterRun();
    if (!isset($this->channelId))
      throw new NgnException('$this->channelId not defined', 1020);
    db()->query('DELETE FROM grabberChannel WHERE id=?d', $this->channelId);
  }
  
}
