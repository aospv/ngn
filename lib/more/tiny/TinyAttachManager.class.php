<?php

class TinyAttachManager {
  
  static protected function getFiles($attachId) {
    return glob(self::getFolderPath($attachId).'/*');
  }
  
  static public function getFolderPath($attachId) {
    return UPLOAD_PATH.'/ed/'.str_replace('-', '/', $attachId); 
  }
  
  static public function getFolder($attachId) {
    return UPLOAD_DIR.'/ed/'.str_replace('-', '/', $attachId); 
  }
  
  /**
   * Выбирает ссылки на файлы и картинки в каталоге UPLOAD_DIR.
   * Все остальные файлы и картинки удалить
   *
   * @param   string  HTML-фрагмент с прикрепленными файлами
   * @param   string  Идентификатор прикрепленных файлов этого HTML-фрагмента
   */
  static public function clearNonUsedAttachFiles($html, $attachId) {
    preg_match_all('/<a.*href=["\']([^"\']+)["\']/U', $html, $m);
    preg_match_all('/<img.*src=["\']([^"\']+)["\']/U', $html, $m2);
    $linkPaths = $m[1];
    $smImagePaths = $m2[1];
    $linkPaths = Arr::append($linkPaths, $smImagePaths);
    
    // Добавляем к массиву файлов, ссылки оригиналов изображений, если конечно они там
    // уже не присутствуют
    foreach ($smImagePaths as $smImagePath) {
      $imagePath = Misc::getFileDisprefexedPath($smImagePath);
      if (!in_array($imagePath, $linkPaths))
        $linkPaths[] = $imagePath;
    }
    die2($linkPaths);
    if (!empty($linkPaths)) {
      foreach ($linkPaths as $link) {
        $linkFiles[] = UPLOAD_PATH.'/'.
          preg_replace('/^\/'.UPLOAD_DIR.'\/(.*)/', '$1', $link);
      }
      if (($files = self::getFiles($attachId))) {
        foreach ($files as $file)
          if (!in_array($file, $linkFiles)) {
            unlink($file);
          }
      }
    } else {
      $folderPath = self::getFolderPath($attachId);
      Dir::remove($folderPath);
      Dir::removeIfEmpty(dirname($folderPath));
    }
  }
  
  static public function getContentMaxWidth() {
    $conf = Config::getVar('dd');
    return !empty($conf['contentWidth']) ? $conf['contentWidth'] : 600;
  }
  
  static public function getImageLinks($html) {
    preg_match_all('/<img.*src=["\']([^"\']+)["\']/U', $html, $m);
    if (!$m[1]) return false;
    return $m[1];
  }
  
  static public function getImageLinksA($html) {
    preg_match_all('/<a [^>]*><img.*src=["\']([^"\']+)["\'].*<\/a>/U', $html, $m);
    if (!$m[1]) return false;
    return $m[1];
  }
  
  static public $thumbTpl = 
    '<a href="[image]" target="_blank"><img src="[mdImage]"></a>';
  
  static public function resizeImages(&$html) {
    /**
     * Получить список картинок в ссылках
     * Получить список картинок без ссылок
     * В ссылках проресайзить
     * Без ссылок проресайзить и вставить в ссылку на оригинал
     */
    
    $imageLinks = self::getImageLinks($html);
    $imageLinksA = self::getImageLinksA($html);
    if (!$imageLinks and !$imageLinksA) return;
    
    $maxWidth = self::getContentMaxWidth();
    
    if ($imageLinksA) {
      foreach ($imageLinksA as $link) {
        $file = WEBROOT_PATH.'/'.self::removeBasePath($link);
        if (!file_exists($file)) {
          self::removeImageA($html, $link);
          continue;
        }
        $sizes = getimagesize($file);
        if ($sizes[0] > $maxWidth) {
          $heigth = round($maxWidth / ($sizes[0] / $sizes[1]));
          O::get('Image')->resampleAndSave(
            $file,
            Misc::replaceExtension($file, 'jpg'),
            $maxWidth,
            $heigth
          );
        }
      }
    }
    
    if ($imageLinks) {
      foreach ($imageLinks as $link) {
        $file = WEBROOT_PATH.'/'.self::removeBasePath($link);
        if (!file_exists($file)) {
          self::removeImage($html, $link);
          continue;
        }
        // try {
          $sizes = getimagesize($file);
        // } catch (NOTT) {
        //  self::removeImage($html, $link);
        //  continue;
        //}
        //if (!$sizes) throw new NgnException("Error reading '$file'");
        
        if ($sizes[0] > $maxWidth) {
          $heigth = round($maxWidth / ($sizes[0] / $sizes[1]));
          O::get('Image')->resampleAndSave(
            $file,
            Misc::getFilePrefexedPath($file, 'md_', 'jpg'),
            $maxWidth, 
            $heigth
          );
          $link = str_replace('.', '\.', $link);
          $link = str_replace('/', '\/', $link);
          $html = preg_replace_callback(
            '/<img[^>]*src=("|\'|)('.$link.')("|\'|)[^>]*>/',
            create_function(
              '$m',
              'return
                str_replace("[image]", $m[2],
                str_replace("[mdImage]", Misc::getFilePrefexedPath($m[2], "md_", "jpg"),
                  \''.self::$thumbTpl.'\'));'
            ),        
            $html
          );
        }
      }
    }
  }
  
  /**
   * Уменьшает размеры изображений, с превышающей шириной
   * Удаляет тэги несуществующих изображений
   * Скачивает изображения со сторонних серверов и меняет ссылку в тэге 
   *
   * @param   string  HTML для обработки
   */
  static public function cleanupImages(&$html, $attachId) {
    set_time_limit_q(0);
    self::downloadRemoteImages($html, $attachId);
    self::resizeImages($html);
    //self::clearNonUsedAttachFiles($html, $attachId);
  }
  
  static public function downloadRemoteImages(&$html, $attachId) {
    if (!($imagePaths = self::getImageLinks($html))) return;
    foreach ($imagePaths as $link)
      if (strstr($link, 'http://'))
        $remoteLinks[] = $link;
    if (empty($remoteLinks)) return;
    $folder = self::getFolder($attachId);
    $folderPath = self::getFolderPath($attachId);
    Dir::make($folderPath);
    $oCurl = new Curl();
    foreach ($remoteLinks as $link) {
      $name = Misc::translate(basename($link));
      try {
        $oCurl->copy($link, $folderPath.'/'.$name);
        $html = Html::replaceParam2($html, 'img', 'src', $link, '/'.$folder.'/'.$name);
      } catch (NgnException $e) {
        self::removeImage($html, $link);
      }
    }
  }
  
  /**
   * Удбирает из ссылки на локальный файл путь до корня сайта вместе с первым слэшем
   * Примеры:
   * http://www.site.com/u/img.png -> u/img.png
   * http://site.com/u/img.png -> u/img.png
   * ./u/img.png -> u/img.png
   * /u/img.png -> u/img.png
   * 
   */
  static public function removeBasePath($url) {
    $url = str_replace('http://'.SITE_DOMAIN, '', $url);
    $url = str_replace('http://www.'.SITE_DOMAIN, '', $url);
    $url = preg_replace('/^\.\/(.*)$/', '$1', $url);
    $url = preg_replace('/^\/(.*)$/', '$1', $url);
    return $url;
  }
  
  static public function removeImage(&$html, $url) {
    $url = str_replace('/', '\/', $url);
    $url = str_replace('.', '\.', $url);
    $html = Html::removeTag($html, 'img', array('src', $url));
  }
  
  static public function removeImageA(&$html, $url) {
    $url = str_replace('/', '\/', $url);
    $url = str_replace('.', '\.', $url);
    $html = preg_replace(
      '/<a[^>]*><img[^>]*src=("|\'|)'.$url.'("|\'|)[^>]*><\/a>/',
      '', $html);
    $html = str_replace('  ', ' ', $html);
  }
  
  static public function moveTempFiles(&$html, $tempAttacheId, $attachId) {
    $tempFolder = self::getFolderPath($tempAttacheId);
    if (!file_exists($tempFolder)) return;
    Dir::move($tempFolder, self::getFolderPath($attachId));
    $html = str_replace(
      self::getFolder($tempAttacheId),
      self::getFolder($attachId),
      $html
    );
  }
  
}
