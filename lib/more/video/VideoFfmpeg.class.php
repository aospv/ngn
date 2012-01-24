<?php

class VideoFfmpeg {

  protected $ffmpeg;
  
  protected $allowedFormats = array(
    '3g2',
    '3gp',
    'aac',
    'aiff',
    'amr',
    'asf',
    'avi',
    'flv',
    'vp6f',
    'mj2',
    'mpeg4',
    'm4a',
    'mpeg',
    'mpeg1video',
    'mpeg2video',
    'mpegvideo',
    'psp',
    'rm',
    'sw',
    'vob',
    'wav',
    'wmv3',
    'mjpeg',
    'yuv4mpegpipe',
    'dvd',
    'h264',
  );
  
  public function __construct() {
    $this->ffmpeg = getOS() == 'win' ?
      dirname(NGN_PATH).'/bin/ffmpeg/bin/ffmpeg.exe' : 'ffmpeg';
  }
  
  protected $info;
  
  public function getInfo($videoFile) {
    if (isset($this->info[$videoFile]))
      return $this->info[$videoFile];
    $info = $this->info[$videoFile] = sys("{$this->ffmpeg} -i $videoFile 2>&1");
    
    if (!$this->_getBitrate($info)) {
      throw new NgnException("$videoFile is not video. No bitrate", 311);
    }
    
    $format = $this->_getFormat($info);
    if (!in_array($format, $this->allowedFormats))
      throw new NgnException("Format '$format' not allowed", 322);
    
    return $this->info[$videoFile];
  }
  
  protected function _getFormat($info) {
    return preg_replace(
      '/.*: Video: ([a-z0-9]+),.*/s', '$1',
      $info
    );
  }
  
  public function getFormat($videoFile) {
    return $this->_getFormat($this->getInfo($videoFile));
  }
  
  public function getMajorBrand($videoFile) {
    return preg_replace(
      '/.*major_brand\s*:\s*([a-z0-9]+).*/s', '$1',
      $this->getInfo($videoFile)
    );
  }
  
  protected $duration;
  
  protected function _getBitrate($info) {
    if (preg_match("/.*bitrate: (.*)\n/", $info, $m)) return $m[1];
    else return false;
  }
  
  public function getBitrate($videoFile) {
    return $this->getInfo($this->_getInfo($videoFile));
  }
  
  public function getDurationStr($videoFile) {
    return preg_replace(
      '/.*Duration: ([0-9:]+)\..*/s', '$1',
      $this->getInfo($videoFile)
    );
  }
  
  public function getDurationSec($videoFile) {
    $d = explode(':', $this->getDurationStr($videoFile));
    $s = (int)$d[0]*60*60 + (int)$d[1]*60 + (int)$d[2];
    return (int)$d[0]*60*60 + (int)$d[1]*60 + (int)$d[2];
  }
  
  public function getSize($videoFile) {
    preg_match('/(\d+)x(\d+)/', $this->getInfo($videoFile), $m);
    return array($m[1], $m[2]);
  }
  
}
