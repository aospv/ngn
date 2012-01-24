<?

//
// Some mplayer routines work class
//
// @uses: mplayer, mencoder, render-new.conf
// 
//

// 
//
// mplayer output example 
//  
// [ID_FILENAME] => 86427.motorbikebackflip.wmv
// [ID_DEMUXER] => asf
// [ID_VIDEO_FORMAT] => WMV3
// [ID_VIDEO_BITRATE] => 0
// [ID_VIDEO_WIDTH] => 320
// [ID_VIDEO_HEIGHT] => 240
// [ID_VIDEO_FPS] => 1000.000
// [ID_VIDEO_ASPECT] => 0.0000
// [ID_AUDIO_CODEC] => ffwmav2
// [ID_AUDIO_FORMAT] => 353
// [ID_AUDIO_BITRATE] => 32048
// [ID_AUDIO_RATE] => 22050
// [ID_AUDIO_NCH] => 2
// [ID_LENGTH] => 35.00

function execInBackground($cmd) {
  if (substr(php_uname(), 0, 7) == "Windows") {
    pclose(popen("start /B " . $cmd, "r"));
  } else {
    return exec($cmd . " > /dev/null &");
  }
}

function _exec($cmd) {
  return execInBackground($cmd);
}

class MplayerCommon {

  public $mplayer = 'mplayer';
  
  const MIN_VBITRATE = 100;
  const MAX_VBITRATE = 1000;
  const ASF_DEMUXER_BITRATE = 1000;
  const DEFAULT_WIDTH = 320;
  const DEFAULT_HEIGHT = 240;
  const DEFAULT_FPS = 24;
  const DEFAULT_ABR = 0;
  
  public function __construct() {
    $this->mplayer = getOS() == 'win' ?
      dirname(NGN_PATH).'/bin/mplayer/mplayer.exe' :
      'mencoder';
  }
  
  function parseFileInfo($file) {
    if (! file_exists($file))
      throw new NgnException('File "'.$file.'" does not exists');
    $cmd = "{$this->mplayer} -quiet -identify -frames 1 $file";
    $out = shell_exec($cmd);
    if (! $out)
      throw new NgnException($cmd);
    foreach (explode("\n", $out) as $s) {
      if (! strstr($s, 'ID_'))
        continue;
      $params[] = $s;
    }
    foreach ($params as $p) {
      $tmp = explode("=", $p);
      $pa[$tmp[0]] = $tmp[1];
    }
    $pa['FILESIZE'] = filesize($file);
    return $pa;
  }

  /**
   * calculating video bitrate by algo from http://www.citizeninsomniac.com/WMV/WMVBitCalc.html with some changes (* 32)
   * 
   * @param int size of file 
   * @param int audio bitrate
   * @param int time length
   * @retunr int
   */
  function calcWmvBitrate($fsize, $audio_bitrate, $length, $demuxer = false) {
    
    $br = round(
      ((($fsize - $audio_bitrate * $length * 0.126) / ($length * 0.126)) / 1000)) *
       32;
    if ($br < self::MIN_VBITRATE || $br > self::MAX_VBITRATE && ! $demuxer)
      return 1000;
    elseif ($br == 0 && ! $demuxer)
      return 1000;
    elseif ($br != 0 && ! $demuxer)
      return $br;
    elseif ($demuxer == 'asf')
      return self::ASF_DEMUXER_BITRATE;
  }

  /**
   * calculate common bitrate
   * @param int bitrate of existing source file
   * @return int
   */
  function getDefaultBitrate($bitrate) {
    $br = round($bitrate / 1024);
    if ($br < self::MIN_VBITRATE || $br > self::MAX_VBITRATE)
      return 1000;
    elseif ($br != 0)
      return $br;
  }

  /**
   * birate resolver
   * @param int video bitrate
   * @param int audio bitrate
   * @param int size of movie file
   * @param int length of movie time
   * @param mixed demuxer
   * @return int
   */
  function getBitrate($bitrate, $audio_bitrate, $fsize, $length, $demuxer = false) {
    if ($bitrate == 0)
      return $this->calcWmvBitrate($fsize, $audio_bitrate, $length, $demuxer);
    else
      return $this->getDefaultBitrate($bitrate);
  }

  function getResolution($w, $h) {
    if ($w < self::DEFAULT_WIDTH || $h < self::DEFAULT_HEIGHT) {
      $res = array(
        $w, 
        $h
      );
    } else {
      $res = array(
        self::DEFAULT_WIDTH, 
        self::DEFAULT_HEIGHT
      );
    }
    return $res;
  }

  function getFrames($fps) {
    if ($fps != 0)
      $fps = round($fps);
    else
      $fps = self::DEFAULT_FPS;
    
    return $fps;
  
  }

  function getAudioBitrate($abr) {
    if ($abr)
      $audio_bitrate = round($abr / 1024);
    else
      $audio_bitrate = self::DEFAULT_ABR;
    
    return $audio_bitrate;
  }

  function ignoreFileTypes($path, $ignore) {
    exec("file " . $path, $out);
    foreach ($ignore as $i) {
      if (strstr($out, $i))
        return true;
    }
    return false;
  }

}
?>