<?php

/**
 * Генератор превьюшек к видео
 */
class VideoPreview extends VideoFfmpeg {
  
  const TYPE_ONE = 1;
  const TYPE_FOUR = 2;

  /**
   * JPEG качество конечной превьюхи
   *
   * @var integer
   */
  public $jpegQuality = 100;
  
  public $addDuration = true;
  
  /**
   * Создаёт JPEG превьюшку из видео файла
   *
   * @param   string  Уникальный идентификатор видео-файла
   * @param   string  Путь до видео-файла
   * @param   integer Ширина конечной превьюшки (в пикселах)
   * @param   integer Высота конечной превьюшки (в пикселах)
   * @param   integer Тип превьюшки (VIDEO_PREVIEW_TYPE_ONE или VIDEO_PREVIEW_TYPE_FOUR)
   */
  public function makePreview($videoFile, $w = 320, $h = 240, $type = 2) {
    if (!file_exists($videoFile))
      throw new NgnException('File "'.$videoFile.'" does not exists');
    $framesCount = $this->getFramesCount($type);

    //$w2 = round($w/2);
    //$h2 = round($h/2);
    //if ($h2%2 != 0) $h2++;
    //if ($w2%2 != 0) $h2++;
    
    $w2 = 320; // с расчитанными размерами появляются белые точки
    $h2 = 240;
    
    for ($frameN=1; $frameN<=$framesCount; $frameN++) {
      $imageFile = File::stripExt($videoFile).'_'.$frameN.'.jpg';
      $time = $this->getFrameStartTime($videoFile, $frameN, $framesCount);
      sys(
        "{$this->ffmpeg} -ss $time -i $videoFile -vcodec mjpeg -vframes 1 ".
        "-an -f rawvideo -y -s {$w2}x{$h2} $imageFile 2>&1", true);
      if (!file_exists($imageFile))
        throw new NgnException("Image file '$imageFile' does not exists");
      $previewFiles[] = $imageFile;
    }
    
    $previewFile = File::reext($videoFile, 'jpg');
    $imageComposer = new ImageComposer();
    $imageComposer->borderWidth = 0;
    $imageComposer->borderHeight = 0;
    $imageComposer->imageMargin = 1;
    $imageComposer->jpegQuality = $this->jpegQuality;
    $imageComposer->destroyAfterSave = false;
    $imageComposer->compose($w, $h, $previewFiles, $previewFile, false);
    
    if (0 and $this->addDuration) {
      imagefilledrectangle(
        $imageComposer->dst,
        $w-50, $h-24, $w-5, $h-5,
        Misc::colorAllocate($imageComposer->dst, '000000')
      );
      $dur = explode(':', $this->getDurationStr($videoFile));
      imagefttext(
        $imageComposer->dst,
        10, 0, $w-46, $h-9,
        Misc::colorAllocate($imageComposer->dst, 'FFFFFF'),
        NGN_PATH.'/fonts/ttf/tahomabd.ttf',
        $dur[1].':'.$dur[2]
      );
      $imageComposer->destroyAfterSave = true;
      $imageComposer->save($imageComposer->dst, $previewFile);
    }
    foreach ($previewFiles as $file) unlink($file);
    return $previewFile;
  }
  
  protected function getFramesCount($type) {
    if ($type == self::TYPE_FOUR)
      return 4;
    else
      return 1;
  }
  
  /**
   * Секунда с которой будет взять первый кадр
   *
   * @var integer
   */
  public $firstFrameSec = 10;
  
  public function getFrameStartTime($videoFile, $frameN, $framesCount) {

    $sec = $this->getDurationSec($videoFile);

    
    $offsetSec = 
      ($sec - $this->firstFrameSec > $this->firstFrameSec) ?
      $this->firstFrameSec : 0;
      
    LogWriter::v('getFrameStartTime', array(
      $videoFile,
      'frame' => $frameN,
      'framesCount' => $framesCount,
      'dur' => $sec,
      'durStr' => $this->getDurationStr($videoFile),
      'offset' => $offsetSec,
      'sec' => round($sec/$framesCount*($frameN-1))
    ));      
      
    return '00:'.Misc::secondsToTimeFormat(round($sec/$framesCount*($frameN-1))+$offsetSec);
  }

}
