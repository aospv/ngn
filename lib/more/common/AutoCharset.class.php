<?php

class AutoCharset {
  
  static $characters;
  
  static function convert($s) {
    $characters = explode(' ',
      '1 2 3 4 5 6 7 8 9 0 '.
      'q w e r t y u i o p a s d f g h j k l z x c v b n m '.
      '; \' " , . { } [ ] ! @ # $ % ^ & ( ) _ - = + ` ~ № ; '.
      'Q W E R T Y U I O P A S D F G H J K L Z X C V B N M '.
      'Ё Й Ц У К Е Н Г Ш Щ З Х Ъ Ф Ы В А П Р О Л Д Ж Э Ю Б Ь Т И М С Ч Я '.
      'ё й ц у к е н г ш щ з х ъ ф ы в а п р о л д ж э ю б ь т и м с ч я'
    );
    $characters[] = ' ';
    self::$characters = $characters;
    foreach (array('cp866', 'cp1251', '') as $encoding) {
      if (($s2 = self::check($s, $encoding)))
        return $s2;
    }
    return false;
  }
  
  static function check($s, $encoding) {
    $s2 = iconv($encoding, CHARSET, $s);
    for ($i=0; $i<mb_strlen($s2, CHARSET); $i++) {
      if (!in_array(mb_substr($s2, $i, 1, CHARSET), self::$characters)) {
        return false;
      }
    }
    return $s2;
  }
  
}