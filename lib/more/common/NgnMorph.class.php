<?php

class NgnMorph {
  
  static $o;
  
  /**
   * @return phpMorphy
   */
  static public function getPhpMorphy() {
    require_once VENDORS_PATH.'/phpmorphy/src/common.php';
    if (isset(self::$o)) return self::$o;
    self::$o = new phpMorphy(
      VENDORS_PATH.'/phpmorphy/dicts',
      'ru_RU'
    );
    return self::$o;
  }
  
  static public function _singular2plural($word) {
    return self::cast($word, array('МН', 'ИМ'));
  }
  
  /**
   * Падежи: ИМ, РД, ТВ, ДТ, ВН, ПР, ЗВ (звательный)
   * 
   * @param  string        Слово
   * @param  array|string  Опции
   */
  static public function _cast($word, $options = array('ЕД', 'ИМ')) {
    $r = self::getPhpMorphy()->castFormByGramInfo(
      mb_strtoupper($word, CHARSET), null, $options, true);
    return mb_strtolower($r[0], CHARSET);
  }
  
  static public function _gender($word, array $genders) {
    $info = self::getPhpMorphy()->getGramInfo(mb_strtoupper($word, CHARSET));
    if ($info[0][0]['grammems'][2] == 'МР')
      return $genders[0];
    if ($info[0][0]['grammems'][2] == 'ЖР')
      return $genders[1];
    if ($info[0][0]['grammems'][2] == 'СР')
      return $genders[2];
  }
  
  static protected function inflect($name) {
    $url = 'http://export.yandex.ru/inflect.xml?name='.urlencode($name);
    $curl = curl_init( $url );
    curl_setopt( $curl, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.6.30 Version/10.61' ); // Just for fun, or ...
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
    $result = curl_exec( $curl );
    curl_close( $curl );
    // Preparing Inflections
    $cases = array();
    preg_match_all( '#\<inflection\s+case\=\"([0-9]+)\"\>(.*?)\<\/inflection\>#si', $result, $m );
    // Creating Inflection List
    if (count($m[0])) {
      foreach ($m[1] as $i => &$id) {
        $cases[(int)$id] = $m[2][$i];
      } unset ($id);
    } else return null;
    // Sending Request Back to User
    if (count($cases) > 1) {
      return $cases;
    } else return false;
  }
  
  static public function _name($name, $case = 2) {
    $cases = self::inflect($name);
    if ($cases && count($cases) > 1) {
      return $cases[$case];
    } else return $name;
  }
  
  static public function __callStatic($name, $args) {
    $id = $name.md5(serialize($args));
    $c = NgnCache::c();
    if (($r = $c->load($id)) !== false) return $r;
    $r = forward_static_call_array(array('self', '_'.$name), $args);
    $c->save($r, $id);
    return $r;
  }
  
}
