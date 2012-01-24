<?php

abstract class GrabberSourceHtmlAbstract extends GrabberSourceAbstract {
  
  /**
   * Ошибка пустого контента. Возникает, если не найдены начало и конец контента
   */
  const ERR_EMPTY_CONTENT = 10;
  
  /**
   * Ошибка пустого контента записи. Возникает, если не найдены начало и конец контента
   */
  const ERR_EMPTY_ITEM_CONTENT = 20;
  
  /**
   * Ошибка пустого контента записи. Возникает, если не найдены начало и конец контента
   */
  const ERR_OUTSIDE_SERVER = 30;
  
  protected function _getHTMLContent(HttpResult $oHttpResult, array $info = array()) {
    $content = $oHttpResult->getBody();
    if (!$content) throw new NgnException('Empty body', 1009);
    $content2 = Html::getInnerContent($content, $info['contentBegin'], $info['contentEnd']);
    if (!$content2) {
      throw new NgnException('Item content is empty from HttpResult. '.
        'Begin: '.htmlspecialchars($info['contentBegin']).', '.
        'End: '.htmlspecialchars($info['contentEnd']).'<br />'.
        'Content:<br />=============<br />'.htmlspecialchars($content),
        self::ERR_EMPTY_CONTENT
      , 1010);
    }
    if (!empty($info['garbage'])) {
      foreach ($info['garbage'] as $item) {
        if (!empty($item['end']))
          $content2 = $this->getClearedContent($content2, $item['begin'], $item['end'], true);
        else
          $content2 = $this->clearContent($content2, $item['begin']);
      }
    }
    return $content2;
  }
  
  /**
   * @param   string  URL
   * @param   array   Пример:
   *                  array(
   *                    'contentBegin' => '',
   *                    'contentEnd' => '',
   *                    'garbage' => array(
   *                      array(
   *                        'begin' => '...',
   *                        'end' => '...',
   *                      )
   *                    )
   *                 )
   * @return  string
   */
  protected function getHTMLContent($url, $info) {
    $oCurl = new Curl();
    if (!($oHttpResult = $oCurl->getObj($url)))
      throw new NgnException('Can not load url "'.$url.'"', 1011);
    /*
    try {
      $finalUrl = $oHttpResult->getFinalUrl();
    } catch (NgnException $e) {
      if ($e->getCode() == HttpResult::ERR_EMPTY_BODY)
        throw new NgnException('Problems with getting body from HttpResult', 1012);
    }
    if ($finalUrl) {
      $finalHost = Misc::getHostUrl($finalUrl);
      if (Misc::getHostUrl($url) != $finalHost)
        throw new NgnException(
          "Can not load url from another server ($finalHost). ".
          "Redirect passed: $url -> $finalUrl",
        self::ERR_OUTSIDE_SERVER, 1013);
    }
    */
    $this->_getHTMLContent($oHttpResult, $info);
  }
  
  /**
   * Убирает из текста ссылки на ресурсы с заданным URL
   *
   * @param   string  Текст для очистки
   * @param   string  URL
   */
  private function stripLinks($text, $url) {
    $purl = parse_url($url);
    return preg_replace('/<a.*href=["|\']http:\/\/'.$purl['host'].'\/.*>(.*)<\/a>/isU', '${1}', $text);
  }
  
  protected function getClearedContent($content, $begin, $end, $flag = false) {
    $begin = str_replace('/', '\\/', $begin);
    $end = str_replace('/', '\\/', $end);
    /*
    if ($flag) pr(array(
      htmlspecialchars($begin),
      htmlspecialchars($end),
      htmlspecialchars($content),
      htmlspecialchars(preg_replace('/'.$begin.'(.*?)'.$end.'/si', '', $content))
    ));
    */
    return preg_replace('/'.$begin.'(.*?)'.$end.'/si', '', $content);
  }
  
  
  protected function getInnerContent($content, $begin, $end) {
    return preg_replace('/.*'.Misc::str2regexp($begin).'(.*)'.Misc::str2regexp($end).'.*/si', '$1', $content);
  }
  
  protected function clearContent($content, $s) {
    return preg_replace('/'.str_replace('/', '\\/', $s).'/si', '', $content);
  }
  
  public function getItemsCount() {
    return -1;
  }
  
  public function getPagesCount() {
    return -1;
  }

  public function getDdItem($itemByLinkData) {
    return $itemByLinkData;
  }

  public function _getListPageItems($page) {
  }
  
  public function downloadDdItemByListPageItem(array $listPageItem) {}
  
}