<?php

/**
 * Class to make links to pages and images clickable
 * 
 * IMPORTANT! 
 * Path to thumbnails for other types of text objects forming by this rules:
 * - Forum message: $pageId-$subjId-$msgId
 * - Comment message: $pageId-$id2-$commentId
 *
 */
class UrlParserThumbs extends UrlParser {
  
  /**
   * Preview for image links width
   * 
   * @var integer
   */
  public $thumbW = 90;
  
  /**
   * Preview for image links height
   * 
   * @var integer
   */
  public $thumbH = 50;
  
  public $baseUrl;
  
  public $basePath;
  
  /**
   * Directory path for temporary images loaded to create preview images
   *
   * @var string
   */
  public $tempImagesDir;
  
  /**
   * Directory path for preview images
   *
   * @var unknown_type
   */
  public $thumbsDir;
  
  public $thumbsSubdir;
  
  public $maxThumbs = 20;
  
  
  /**
   * HTML witch will be inserted after the images block
   *
   * @var string
   */
  public $imagesEndHtml = '<div class="clear"><!-- --></div>';
  
  public $imageBeginHtml = '<div class="image">'; 
  
  public $imageEndHtml = '</div>';
  
  public $imagesCounter = 0;
  
  public $imagesGroupId;
  
  /**
   * Если этот флаг включен, ссылки на картинки будут заменены их превьюшками
   *
   * @var bool
   */
  public $parseImages = true;
  
  function __construct($basePath, $baseUrl, $tempImagesDir, $thumbsDir, $thumbsSubdir) {
    $this->basePath = $basePath;
    $this->baseUrl = $baseUrl;
    $this->tempImagesDir = $tempImagesDir;
    $this->thumbsDir = $thumbsDir;
    $this->imagesGroupId = str_replace('/', '-', $thumbsDir);
    if ($thumbsSubdir[strlen($thumbsSubdir)-1] != '/') $thumbsSubdir .= '/';
    if (!is_dir($basePath.$thumbsDir.$thumbsSubdir)) {
      if (!$this->createDirByPath($basePath.$thumbsDir, $thumbsSubdir)) {
        $this->parseImages = false;
      }
    }
    $this->thumbsDir = $thumbsDir.$thumbsSubdir;
  }
  
  function createDirByPath($base, $path) {
    if (!is_dir($base)) {
      if (!Dir::make($base)) Err::warning("Can't make '$base' dir");
    }
    if (!$path) throw new NgnException('$path undefined');
    if (!strstr($path, '/')) mkdir($base.$path);
    $pathParts = explode('/', $path);
    for ($i=0; $i<count($pathParts); $i++) {
      if ($pathParts[$i] and !is_dir($base.$pathParts[$i])) {
        if (!@mkdir($base.$pathParts[$i])) return false;
      }
      $base .= $pathParts[$i].'/';
    }
    return $base;
  }
  
  /**
   * Generates HTML with clickable links
   *
   * @param   string  String witch will be parsed
   * @param   string  Images group ID. It mast be unical idetificator for images, founded in $str
   * @return  string  HTML text
   */
  function makeClickableLinks($str) {
    $html = parent::makeClickableLinks($str);
    $html = $this->closeImageGroups($html);
    $html = str_replace('[iiBegin]', $this->imageBeginHtml, $html);
    $html = str_replace('[iiEnd]', $this->imageEndHtml, $html);
    return $html;
    /*
    return $this->closeImageBlocks(
      parent::makeClickableLinks($str),
      $this->imageBeginHtml,
      $this->imageEndHtml
    );
    */
  }
  
  /**
   * Format and retuns HTML code by matches parsed from link 
   * witch starts from 'http://' string.
   * Uses in parent::makeClickableLinks() method
   *
   * @param   array Found by regexp parsing matches
   * @return  string
   */
  function getMatchesHTTP($matches) {
    // Adds 'target="_blank"' if it is external link, and removes absolute path part from internal links
    if (strstr($matches[0], $this->baseUrl)) {
      //$matches[0] = str_replace($this->baseUrl, '/', $matches[0]);
      //$internal = true;
    } elseif (strstr($matches[0], 'http://')) {
      $target = ' target="_blank"';
    }
    // If it is image link
    if ($this->parseImages and preg_match('/(.*)(\.gif|\.jpg|\.jpeg|\.png|\.bmp)$/i', $matches[0])) {
      $imagePath = $internal ? substr($this->baseUrl, 0, strlen($this->baseUrl)-1).$matches[0] : $matches[0];
      if (!$thumbPath = $this->getThumbImage($imagePath)) return $imagePath;
      if (!strstr($imagePath, 'http://')) $imagePath = '/'.$imagePath;
      return $this->getThumbHtml($thumbPath, $imagePath);
    // If it is page link
    } else {
      return '<a href="'.$matches[0].'"'.$target.'>'.Misc::cut($matches[0], 50).'</a>';
    }
  }
  
  private function getThumbHtml($thumbPath, $imagePath) {
    return '[iiBegin]<a href="'.$imagePath.'" target="_blank"><img src="'.$thumbPath.'"></a>[iiEnd]';
  }

  /**
   * Returns unical base name for current images group.
   * Depends on witch text-object uses parser.
   *
   */
  private function getImageBaseName() {
    $this->imagesCounter++; // iterate image counter
    return $this->imagesCounter;
  }
  
  /**
   * Загружает картинку с указанного адреса, создаёт превьюшку и возвращает путь к ней.
   * После этого исходная картинка удаляется из временной директории
   *
   * @param   string    Ссылка на изображение
   * @return  mixed
   */
  private function getThumbImage($imageLink) {
    if ($this->imagesCounter+1 > $this->maxThumbs) return false;
    if (!$imageData = @file_get_contents($imageLink)) return false;
    $name = $this->getImageBaseName();
    $smImageName = $name.'.jpg';
    $imagePath = $this->basePath.'/'.$this->tempImagesDir.$this->imagesGroupId.$name.'.tmp';
    $smImagePath = $this->basePath.$this->thumbsDir.$smImageName;
    file_put_contents($imagePath, $imageData);
    $image = new Image();
    $image->resizeAndSave($imagePath, $smImagePath, $this->thumbW, $this->thumbH);
    unlink($imagePath);
    return '/'.$this->thumbsDir.$smImageName;
  }
  
  private function closeImageGroups($text) {
    $text = str_replace('[iiEnd]<br />', '[iiEnd]', $text);
    return preg_replace('/(.*\[iiEnd\])(?!\s*\[iiBegin\])(.*)?/U', '${1}'.$this->imagesEndHtml.'${2}', $text);
  }

  private function closeImageBlocks_($str, $blockBegin, $blockEnd) {
    $str = ' '.$str; // to strpos() at first iteration as the begining of string not returns 'false'
    $lastEndPos = 0;
    $firstBeginPos = 0;
    $n = 0;
    $pos1 = strpos($str, $blockBegin, $lastEndPos);
    while (1) {
      if (!$pos1 = strpos($str, $blockBegin, $lastEndPos)) break;
      $n++;
      $pos2 = strpos($str, $blockEnd, $lastEndPos);
      // Проверяем есть ли после него текст, или идёт следующий тэг изображения
      $pos2end = $pos2 + strlen($blockEnd);
      $m=0;
      for ($i=$pos2end; $i<strlen($str); $i++) {
        $m++;
        if ($str[$i] != ' ' and $str[$i] != "\n" and $str[$i] != "\r") {
          if (substr($str, $i, strlen($blockBegin)) == $blockBegin) break;
          $imageBlocksEnd[] = $pos2end;
          break;
        }
      }
      if (!$firstBeginPos) $firstBeginPos = $pos1;
      $lastEndPos = $pos2end;
    }
    if (!$n) return trim($str);
    if (!$imageBlocksEnd) return trim($str.$this->imagesEndHthml);
    if (!in_array($pos2end, $imageBlocksEnd)) $imageBlocksEnd[] = $pos2end;
    $lastEndPos = 0;
    for ($i=0; $i<count($imageBlocksEnd); $i++) {
      $parts[] = substr($str, $lastEndPos, $imageBlocksEnd[$i]-$lastEndPos);
      $lastEndPos = $imageBlocksEnd[$i];
    }
    if (strlen($str) > $lastEndPos) {
      $parts[] = substr($str, $lastEndPos, strlen($str));
    }
    for ($i=0; $i<count($parts); $i++) $parts[$i] .= $this->imagesEndHthml;
    return trim(implode('', $parts));
  }
  
}
