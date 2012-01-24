<?php

class VideoDecoder extends VideoFfmpeg {

  protected $ffmpeg;
  protected $presetsFolder;
  
  public function __construct() {
    parent::__construct();
    $this->presetsFolder = dirname(NGN_PATH).'/bin/ffmpeg/presets';
  }
  
  public function decode($inputFile, $outputFile, $w = 320, $h = 240) {
    list($initW, $initH) = $this->getSize($inputFile);
    if ($initW > $w or $initH > $h) {
      if ($initW / $initH > $w / $h) {
        $h = round($w / ($initW / $initH));
      } else {
        $w = round($h / ($initH / $initW));
      }
    } else {
      $w = $initW;
      $h = $initH;
    }
    
    /*
    // FLV:
    $cmd = "{$this->ffmpeg} -i $inputFile ".
      "-ar 44100 -ab 32 ". // audio codec
      "-f flv -b 400 ".           // video codec
      "-s {$w}x{$h} $outputFile";
    */
    
    $cmd = 
      "{$this->ffmpeg} -i $inputFile ".
      "-acodec libfaac -ab 128k -ar 44100 ". // audio codec
      "-vcodec libx264 -vpre slow -vpre baseline -crf 22 -threads 0 ".  // video codec
      //"-s {$w}x{$h} ".
      //"-r 29 ".
      "$outputFile";
    
    File::delete($outputFile);
    sys($cmd);
    
    sys("MP4Box -add $outputFile $outputFile");
    
    if (!file_exists($outputFile))
      throw new NgnException(
        'Decode video problems. Video "'.$inputFile.'". Command: '.$cmd, 1038);
  }
  
}
