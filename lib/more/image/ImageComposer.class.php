<?php

define('ERROR_IMAGE_COMPOSER_MIX_EXESS', 1);
define('ERROR_IMAGE_COMPOSER_MAX_EXESS', 2);
define('ERROR_IMAGE_SMALL_SIZES_LESS_ZERO', 3);

class ImageComposer extends Image {
  
  public $errors;
  public $errorsText;
  public $maxImagesInComposition = 8;
  public $minImagesInComposition = 2;
  public $borderWidth = 24;
  public $borderHeight = 24;
  public $imageMargin = 2;
  public $folderBgImage = null;
  
  public function __construct() {
    $this->errorsText = array(
      ERROR_IMAGE_COMPOSER_MIX_EXESS => 'Min exess',
      ERROR_IMAGE_COMPOSER_MAX_EXESS => 'Maz exess',
      ERROR_IMAGE_SMALL_SIZES_LESS_ZERO => 'Less zero',
    );
  }
  
  //////////////////////////////////////////////////////////////////////

  // 30x20 sm
  public $mozaicW = 3543;
  public $mozaicH = 2362;
  public $mozaicDepth;
  public $mozaicMaxDepth = 20;
  protected $mozaicImage;
  
  /**
   * Создавать несколько файлов мозаик, если изображения не умещаются на одну
   *
   * @var bool
   */
  public $mozaicSplitFiles = true;
  
  public function mosaic($smW, $smH, $dir, $resultPath) {
    $this->_mosaic($smW, $smH, Dir::getFilesR($dir, '*.jpg'), $resultPath);
  }
  
  public function _mosaic($smW, $smH, $images, $resultPath) {
    $this->mozaicDepth = 0;
    $this->mosaicR($smW, $smH, $images, $resultPath);
  }
  
  public function mosaicR($smW, $smH, $_images, $resultPath) {
    if (empty($_images))
      throw new NgnException('$_images is empty');
    $this->mozaicDepth++;
    if ($this->mozaicDepth == $this->mozaicMaxDepth)
      die2('Max depth!');
    $maxInRow = round($this->mozaicW / $smW);
    $maxInCol = round($this->mozaicH / $smH);
    if ($maxInRow == 0) throw new NgnException('$maxInRow = 0');
    if ($maxInCol == 0) throw new NgnException('$maxInCol = 0. $this->mozaicH='.$this->mozaicH.', $smH='.$smH);
    $mozaicImageDest = imageCreateTrueColor($smW * $maxInRow, $smH * $maxInCol);
    $top = 0;
    $left = 0;
    $n = 0;
    $imagesInRectangle = $maxInRow * $maxInCol;
    $images = array();
    foreach ($_images as &$v) if (file_exists($v)) $images[] = $v;
    if (count($images) and count($images) < $imagesInRectangle) {
      // Если количество изображений для мозаики меньше необходимого для 
      // заполнения полной мозаики... 
      $this->resizeAndSave($images[0], $resultPath, ($maxInRow * $smW), ($maxInCol * $smH));
      return;
    }
    for ($i=0; $i<count($images); $i++) {
      if ($n != 0 and $n % $maxInRow == 0) {
        $top += $smH;
        $left = 0;
      }
      if ($n != 0 and $i % ($maxInRow * $maxInCol) == 0) {
        // Дошли до заполненного состояния мозаики
        // Формируем массив с оставшимися фотками для передачи в эту же функцию
        // и выходим из цикла формирования мозаики
        $nextImages = array();
        for ($ii = $i; $ii<count($images); $ii++)
          $nextImages[] = $images[$ii];
        break;
      }
      $mozaicImageSrc = $this->resize($images[$i], $smW, $smH);
      imageCopy($mozaicImageDest, $mozaicImageSrc, $left, $top, 0, 0, $smW, $smH);
      $left += $smW;
      $n++;
    }
    $this->save($mozaicImageDest, $resultPath);
    
    // Рекурсия. Создает несколько изображений мозаик, если 
    // все картинки не убираются на одну мозаику. Имена файлов заканчиваются 
    // номерами мозаик.
    if ($this->mozaicSplitFiles and isset($nextImages))
      $this->mosaicR($smW, $smH, $nextImages,
        preg_replace('/(.+)(\.\w+)/', '$1-'.($this->mozaicDepth+1).'$2', $resultPath));
  }
  
  public function compose($w, $h, $images, $resultPath, $resize = true) {
    if (count($images) < $this->minImagesInComposition)
      throw new NgnException("Minimal images count in composition is {$this->minImagesInComposition}, total count is ".count($images));
    
    if (count($images) > $this->maxImagesInComposition)
      throw new NgnException("Maximal images count in composition is {$this->maxImagesInComposition}, total count is ".count($images));
    
    $n = count($images);
    
    //if ($w / $h > 0) $horisontal = true;
    //else $horisontal = false;
    
    if ($n <= 4) {
      if ($resize) {
        $smW = round($w / 2) - $this->imageMargin - $this->borderWidth;
        $smH = round($h / 2) - $this->imageMargin - $this->borderHeight;
      } else {
        $smW = round($w / 2);
        $smH = round($h / 2);
      }
    } else {
      die("Algorithm for $n images not yet supported.");
    }
    
    if ($smW < 0 or $smH < 0) {
      $this->errors[] = $this->errorsText[ERROR_IMAGE_SMALL_SIZES_LESS_ZERO];
      return false;      
    }
    
    if ($resize) {
      for ($i=0; $i<count($images); $i++) {
        if (is_file($images[$i])) {
          $this->resize($images[$i], $smW, $smH);
          $sm[$i] = $this->dst;
        }
      }   
    } else {
      for ($i=0; $i<count($images); $i++) {
        if (is_file($images[$i])) {
          if (!($sm[$i] = imageCreateFromJpeg($images[$i]))) {
            throw new NgnException('Create JPEG from "'.$images[$i].'" error');
          }
        }
        else throw new NgnException("File '{$images[$i]}' not exists");
      }
    }
    
    if ($this->folderBgImage) $this->dst = imageCreateFromJpeg($this->folderBgImage);
    else $this->dst = imageCreateTrueColor($w, $h);
    
    $left = $this->borderWidth;
    $top = $this->borderHeight;
    $left2 = round($w / 2) + $this->imageMargin;
    $top2 = round($h / 2) + $this->imageMargin;
    
    if ($sm[0]) imageCopy($this->dst, $sm[0], $left, $top, 0, 0, $smW, $smH);
    if ($sm[1]) imageCopy($this->dst, $sm[1], $left2, $top, 0, 0, $smW, $smH);
    if ($sm[2]) imageCopy($this->dst, $sm[2], $left, $top2, 0, 0, $smW, $smH);
    if ($sm[3]) imageCopy($this->dst, $sm[3], $left2, $top2, 0, 0, $smW, $smH);
    $this->save($this->dst, $resultPath);
    return true;
  }
  
}
