<?php

class ImageWatermark {
  
  public $bootomOffset;
  public $rightOffset;
  public $jpegQuality = 100;
  public $watermarkImagePath;
  
  function __construct($watermarkImagePath, $rightOffset, $bootomOffset) {
    $this->rightOffset = $rightOffset;
    $this->bootomOffset = $bootomOffset;
    $this->watermarkImagePath = $watermarkImagePath;
  }

  function make($imagePath) {
    if (!file_exists($this->watermarkImagePath))
      throw new NgnException('Watermark image "'.$this->watermarkImagePath.'" does not exists');
    $watermarkPath = $this->watermarkImagePath;
    $watermarkInfo =  getimagesize($watermarkPath);
    if ($watermarkInfo['mime'] == 'image/jpeg') $srcImg = imagecreatefromjpeg($watermarkPath);
    elseif ($watermarkInfo['mime'] == 'image/gif') $srcImg = imagecreatefromgif($watermarkPath);
    elseif ($watermarkInfo['mime'] == 'image/png') $srcImg = imagecreatefrompng($watermarkPath);
    elseif ($watermarkInfo['mime'] == 'image/wbmp') $srcImg = imagecreatefromwbmp($watermarkPath);
    else
      throw new NgnException('Unexpected mime type of watermark image');
    $imageInfo =  getimagesize($imagePath);
    if ($imageInfo['mime'] == 'image/jpeg') $dstImg = imagecreatefromjpeg($imagePath);
    elseif ($imageInfo['mime'] == 'image/gif') $dstImg = imagecreatefromgif($imagePath);
    elseif ($imageInfo['mime'] == 'image/png') $dstImg = imagecreatefrompng($imagePath);
    elseif ($imageInfo['mime'] == 'image/wbmp') $dstImg = imagecreatefromwbmp($imagePath);
    else
      throw new NgnException('Unexpected mime type of source image');
    $srcW = ImageSX($srcImg);
    $srcH = ImageSY($srcImg);
    $dstW = ImageSX($dstImg);
    $dstH = ImageSY($dstImg);
    $srcX = 0;
    $srcY = 0;    
    $dstX = $dstW - $this->rightOffset - $srcW;
    $dstY = $dstH - $this->bootomOffset - $srcH;
    imagecopy($dstImg, $srcImg, $dstX, $dstY, $srcX, $srcY, $srcW, $srcH);
    imagejpeg($dstImg, $imagePath, $this->jpegQuality);
    return true;
  }

}
