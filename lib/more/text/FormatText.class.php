<?php

class FormatText extends Options2 {
  
  /**
   * @var Jevix
   */
  public $oJevix;
  
  public $thumbsDir;
  
  public $iiSizes;
  
  public $options = array(
    'allowedTagsConfigName' => 'tiny.site.allowedTags'
  );
  
  public function init() {
    $this->oJevix = new Jevix();
    $this->oJevix->cfgSetTagCutWithContent(array('script', 'iframe', 'style'));
    $this->iiSizes = Config::getVar('iiSizes');
    $this->oJevix->cfgSetAutoBrMode(false);
  }
  
  public function __call($method, $args) {
    if (Misc::hasPrefix('cfg', $method)) {
      call_user_func_array(array($this->oJevix, $method), $args);
      return $this;
    }
    return call_user_func_array(array($this, $method), $args);
  }

  /**
   * Очищает, типографирует HTML
   *
   * @param   string    HTML
   * @param   string    Директория для картинок. Пример: 13/22/333
   * @return  string    Преобразованный HTML
   */
  public function html($text) {  
    $text = trim($text);
    $text = str_replace(array(
      "\r",
      //"\n",
      "<hr /><br />",
      "</div><br />",
      "[b]",
      "[/b]",
      "[i]",
      "[/i]"
    ), array(
      "",
      //"<br/>",
      "<hr/>",
      "</div>",
      "<b>",
      "</b>",
      "<i>",
      "</i>"
    ), $text);
    if (isset($this->thumbsDir)) {
      $urlParser = new UrlParserThumbs(
        WEBROOT_PATH,
        SITE_WWW,
        UPLOAD_DIR.'/'.INLINE_IMAGES_TEMP_DIR,
        UPLOAD_DIR.'/'.INLINE_IMAGES_THUMB_DIR,
        $this->thumbsDir
      );
      $urlParser->thumbW = $this->iiSizes['w'];
      $urlParser->thumbH = $this->iiSizes['h'];
      $text = $urlParser->makeClickableLinks($text);
    } else {
      $urlParser = new UrlParser();
      $text = $urlParser->makeClickableLinks($text);
    }
    $text = str_replace("</quote><br />", "</quote>", $text);
    $text = str_replace("</quote><br />", "</quote>", $text);
    $text = str_replace('http://mailto:', 'mailto:', $text);
    $text = $this->cleanHtml($text);
    return $text;
  }
  
  /**
   * Типографиреут текст
   *
   * @param   string   Текст
   * @return  string   Преобразованный текст
   */
  public function typo($text) {
    return $this->cleanText($text);
  }
  
  protected function cleanText($text) {
    $errors = array();
    return $this->oJevix->parse($text, $errors);
  }
  
  protected function cleanHtml($html) {
    $tags = array();
    $params = array();
    if (($confTags = Config::getVar($this->options['allowedTagsConfigName'])) !== false) {
      foreach ($confTags as $v) {
        $v = str_replace(',', '|', $v);
        $v = strtolower($v);
        if (preg_match('/^([a-z0-9]+)\[([a-z][a-z0-9|]*)\]$/', $v, $m)) {
          $tags[] = $m[1];
          $params[$m[1]] = explode('|', $m[2]);
        } elseif (preg_match('/^([a-z0-9]+)$/', $v, $m)) {
          $tags[] = $m[1];
        }
      }
    }
    $this->oJevix->cfgAllowTags($tags);
    if ($params) {
      foreach ($params as $tag => $pms) {
        $this->oJevix->cfgAllowTagParams($tag, $pms);
      }
    }
    foreach (array('br', 'hr', 'img') as $v)
      if (in_array($v, $tags)) $shortTags[] = $v;
    $this->oJevix->cfgSetTagShort($shortTags ? $shortTags : array());

    foreach (array('param', 'embed', 'td') as $v)
      if (in_array($v, $tags)) $emptyTags[] = $v;
    $this->oJevix->cfgSetTagIsEmpty(isset($emptyTags) ? $emptyTags : array());

    if (in_array('a', $tags)) $this->oJevix->cfgSetTagParamsRequired('a', 'href');
    $errors = array();
    $html = $this->oJevix->parse($html, $errors);
    $html = str_replace('</quote><br/>', '</quote>', $html);
    return $html;
  }
  
}
