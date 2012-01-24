<?php

class PmiSitemap extends Pmi {
  
  public $title = 'Карта сайта';
  public $controller = 'tpl';
  public $oid = 40;
  public $onMenu = false;

  protected function getSettings() {
    return array(
      'tplName' => 'sitemap'
    );
  }

}
