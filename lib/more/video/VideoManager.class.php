<?php

class VideoManager {
  
  /**
   * @var VideoDecoder
   */
  protected $oVideoDecoder;
  
  /**
   * @var VideoPreview
   */
  protected $oVideoPreview;
  
  public $notConvertH264AndFlv = true;
  
  public function __construct() {
    $this->oVideoDecoder = new VideoDecoder();
  }
  
  /**
   * Проверяет является ли файл видео
   *
   * @param   string  Видео файл
   */
  public function check($file) {
    //$this->oVideoDecoder->getI
  }
  
  public function make($inputVideoFile, $outputFolder, $w, $h) {
    $inputFormat = $this->oVideoDecoder->getFormat($inputVideoFile);
    //$inputMajorBrand = $this->oVideoDecoder->getMajorBrand($inputVideoFile);
    
    /*
    if ($inputFormat == 'flv')
      $ext = 'flv';
    elseif ($inputFormat == 'h254')
      $ext = 'mpg';
    else
      $ext = 'mp4';
    */
    
    
    $outputVideoFile = 
      $outputFolder.'/'.File::reext(basename($inputVideoFile), 'mp4');
    
    //LogWriter::v('Format', $inputFormat);
    
    
    // Конвертируем видео только в том случае, если флаг "notConvertH264AndFlv"
    // выключен или формат исходного не является "h264" или "flv"
    if ($this->notConvertH264AndFlv and
    (
      ($inputFormat == 'h264'/* and $inputMajorBrand == 'mp42'*/) or
       $inputFormat == 'flv')
    ) {
      copy($inputVideoFile, $outputVideoFile);
    } else {
      $this->oVideoDecoder->decode(
        $inputVideoFile,
        $outputVideoFile,
        $w, $h
      );
    }
    return $outputVideoFile;
  }
  
}
