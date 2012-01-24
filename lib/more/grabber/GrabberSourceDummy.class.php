<?php

class GrabberSourceDummy extends GrabberSourceHtmlAbstract {

  static $title = 'Dummy';
  
  public function _getListPageItems($page) {
    $content = Html::getInnerContent(
      iconv('windows-1251', CHARSET, file_get_contents(
        'http://padonki.org/archieve/diagnosis/2006/0.do')),
      '<!--triple content structure-->',
      '<!--%%content%%-->'
    );
    foreach (phpQuery::newDocument($content)->find('a') as $v) {
      print pq($v)->attr('href')."\n";
    }
    die2(htmlspecialchars($content));
  }
  
  public function getItemsCount() {}
  public function getPagesCount() {
    return 10;
  }
  public function downloadDdItemByListPageItem(array $listPageItem) {}
  
}
