<?php

class Speaker {

  static public function speak($name) {
    $binFolder = dirname(dirname(dirname(dirname(__DIR__)))).'/bin';
    exec($binFolder.'/wv_player/wv_player.exe '.
         $binFolder.'/wv_player/sounds/'.$name.'.mp3');
  }

  static public function digit($str) {
    $str = preg_replace('/[a-z]*(\d+)[a-z]*/', '$1', $str);
    //print "\n\n***** $str ***** \n\n";
    for ($i=0; $i<strlen($str); $i++)
      self::speak($str[$i]);
  }
  
}
