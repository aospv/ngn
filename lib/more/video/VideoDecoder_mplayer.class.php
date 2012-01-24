<?

class VideoDecoder {

  public $vbitrate = 2000;
  public $fps = 30;
  public $audioBitrate = 64;
  
  /**
   * @var MplayerCommon
   */
  public $mp;
  
  public function __construct() {
    $this->mp = new MplayerCommon();
  }

  public function decode($inFilename, $outFilename, $w = 240, $h = 180) {
    $mp = $this->mp;
    
    if (!$info = $mp->parseFileInfo($inFilename))
      throw new NgnException('Mplayer error. Maby wrong format');
    
    if (!$info['ID_LENGTH'])
      throw new NgnException('Wrong format');
    
    if ($info['ID_DEMUXER'] == 'asf') $demuxer = 'asf';
    else $demuxer = false;    
    
    $this->vbitrate = $mp->getBitrate(
      $info['ID_VIDEO_BITRATE'],
      $info['ID_AUDIO_BITRATE'],
      filesize($inFilename),
      $info['ID_LENGTH'],
      $demuxer
    );
    $fps_string = "-ofps ".$this->fps;
    if (getOS() == 'win')
      $mencoder = dirname(dirname(dirname(dirname(__DIR__))))."/bin/mplayer/mencoder.exe";
    else
      $mencoder = "mencoder";
    
    
    
    /*
    // DivX4 codec
    $cmd = "D:\a\DIS\_Multimedia\mplayer\mencoder.exe " . $inFilename
    . " -o " . $outFilename.".mpeg"
    . " -quiet"
//    . " -of lavf -oac mp3lame -lameopts abr:br=".$this->audioBitrate." -srate 22050"
    . " -ovc divx4 ";
//    . " -lavcopts vcodec=mjpeg -oac copy";

/////////////////////////////////////////////////

    $cmd = "$mencoder $inFilename -o $outFilename.mpeg -quiet"
    . " -of lavf -oac mp3lame -lameopts abr:br=".$this->audioBitrate." -srate 22050"
    . " -ovc xvid -xvidencopts bitrate=900:autoaspect";


    */
//    -ovc xvid -xvidencopts bitrate=$V_BITRATE:autoaspect

      /**
       * 
       * 
       * 
       * Example syntax for FFmpeg: 
:
ffmpeg.exe -i input_file.avi -vcodec flv -me full -me_range 16 -mbd 1 -qmin 2 -qmax 31 -b 500000 -r 25 -s 320x240 -refs 5 -acodec mp3 -ar 22050 -ac 2 -ab 128 ouput_file.flv



And very simple: 
:
ffmpeg.exe -i input_file.avi output_file.flv
       * 
       * 
       */

      $vbitrate = $this->vbitrate;
      $vbitrate = 1000;
    ///////////////////////////////////////////////////////////////////
    // Flash Video Codec
    $cmd = "$mencoder " . $inFilename
    . " -o " . $outFilename
    . " -quiet"
    . " -of lavf -oac mp3lame -lameopts abr:br=".$this->audioBitrate." -srate 22050"
    . " -ovc lavc -lavfopts i_certify_that_my_video_stream_does_not_use_frames"
    . " -lavcopts vcodec=flv:keyint=50:vbitrate=".$vbitrate.":mbd=2:mv0:trell:v4mv:cbp:last_pred=3"
    . " -vf scale=$w:$h ".$fps_string;
    
    $cmd = "$mencoder -ofps 25 $inFilename -o $outFilename ".
    " -of lavf -lavfopts i_certify_that_my_video_stream_does_not_use_b_frames:format=flv -ovc ".
    "lavc -lavcopts vcodec=flv:autoaspect:vbitrate=$vbitrate:vqmin=2:vqmax=31:vme=5:".
    "mbd=1:subq=5:keyint=250 -vf scale=$w:$h ".//-vf scale=$w:$h
    "-af resample=22050:0:0,channels=2 -oac mp3lame -lameopts ".//-ofps {$this->fps} 
    "vbr=2:q=6:aq=2:highpassfreq=-1:lowpassfreq=-1";
    
    //    . " -lavcopts vcodec=mpeg1video:vbitrate=1152:keyint=15:mbd=2:aspect=4/3";
    //    . " -vf scale=$w:$h ".$fps_string;
    
    /*

    $cmd = "$mencoder $inFilename -of mpeg -mpegopts format=mpeg1:tsaf:muxrate=2000"
    . " -o $outFilename.mpg -oac lavc -lavcopts acodec=mp2:abitrate=224 -ovc lavc "
    . " -lavcopts vcodec=mpeg1video:vbitrate=1152:keyint=15:mbd=2:aspect=4/3";

    $cmd = "$mencoder $inFilename -o $outFilename.mpg -ofps 25 -vf scale=352:288,harddup -of lavf"
    ." -lavfopts format=mpg -oac lavc -lavcopts acodec=mp2:abitrate=224 -ovc lavc "
    ." -lavcopts vcodec=mpeg1video:vrc_buf_size=327:keyint=15:vrc_maxrate=1152:vbitrate=1152:vma?_frames=0";

    $cmd = "$mencoder $inFilename -of mpeg -ovc lavc -lavcopts"
    ." vcodec=mpeg1video"
    ." -oac copy other_options -o $outFilename.mpg";
    */
    
    $s = exec($cmd, $ar, $rc);
    
    if (strstr($s, 'error'))
      throw new NgnException($s."\nCommand: $cmd");

    if ($rc > 0) return $rc;
    return true;
  }

}

