<?php

class DfieldRssUrl extends DfieldAbstract {

  public function f() {
    if (!($v = parent::f())) return false;
    // Валидация
    if (!Misc::validUrl($v)) {
      $this->error("'$v' не является правиьлной ссылкой");
    }
    $feedReader = new FeedReader();
    $feedReader->setFeedUrl($v);
    if (!$feedReader->parseFeed()) {
      $this->error('При чтении данного rss-канала произошли ошибки');
    }
    return $v;
  }

}