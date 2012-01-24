<?php

class Arr {

  static public function append(array $arr1, array $arr2, $withoutRepetitions = false) {
    for ($i = 0; $i < count($arr2); $i++) {
      if ($withoutRepetitions and in_array($arr2[$i], $arr1)) // Если уже присутствует
        continue;
      $arr1[] = $arr2[$i];
    }
    return $arr1;
  }
  
  static public function assoc(array $arr, $k, $multi = false) {
    $res = array();
    foreach ($arr as $v)
      $multi ? $res[$v[$k]][] = $v : $res[$v[$k]] = $v;
    return $res;
  }
  
  static public function drop(array $arr, $v) {
    for ($i = 0; $i < count($arr); $i++) {
      if ($arr[$i] != $v)
        $arr2[] = $arr[$i];
    }
    return isset($arr2) ? $arr2 : array();
  }
  
  static public function dropK(array $arr, $k) {
    unset($arr[$k]);
    return $arr;
  }
  
  static public function dropBySubKey(array $arr, $k, $v, $assoc = false) {
    $new = array();
    foreach ($arr as $key => $val) {
      if (isset($val[$k]) and $val[$k] == $v) {
        continue;
      }
      $assoc ? $new[$key] = $val : $new[] = $val;
    }
    return $new;
  }
  
  static public function dropArr(array &$arr, array $arr3) {
    for ($i = 0; $i < count($arr); $i++) {
      if (!in_array($arr[$i], $arr3))
        $arr2[] = $arr[$i];
    }
    return $arr = $arr2 ? $arr2 : array();
  }
  
  static public function dropN(array &$arr, $n) {
    $arr2 = array();
    for ($i = 0; $i < count($arr); $i++) {
      if ($i != $n)
        $arr2[] = $arr[$i];
    }
    $arr = $arr2;
  }
  
  static function dropCallback(array $arr, $func) {
    foreach ($arr as $v) {
      if (!$func($v)) $r[] = $v;
    }
    return $r;
  }

  
  /**
   * Вынимает значения из элементов хэша и возвращает их как массив 
   *
   * @param   array   Массив с массивами
   * @param   string  Ключ элемента подмассива, элемент которого необходимо использовать, 
   *                  как элемент результирующего массива
   * @param   string  Ключ элемента подмассива, значение которого необходимо 
   *                  использовать в качестве ключа результирующего массива
   * @return  array
   */
  static public function get(array $arr, $k, $kk = null) {
    $res = array();
    if ($kk == 'KEY')
      foreach ($arr as $KEY => $v)
        $res[$KEY] = is_array($v) ? $v[$k] : $v->$k;
    elseif ($kk)
      foreach ($arr as $v)
        $res[$v[$kk]] = is_array($v) ? $v[$k] : $v->$k;
    else {
      foreach ($arr as $v) {
        $res[] = is_array($v) ? $v[$k] : $v->$k;
      }
    }
    return $res;
  }
  
  static public function get_value(array $arr, $k1, $v1, $k2) {
    foreach ($arr as $v) {
      if ($v[$k1] == $v1)
        return $v[$k2];
    }
    return false;
  }
  
  static public function getValue(array $arr, $key) {
    foreach ($arr as $k => $v)
      if ($k == $key)
        return $v;
    return false;
  }
  
  static public function getValueByKey(array $arr, $k1, $v1) {
    foreach ($arr as $v) {
      if ($v[$k1] == $v1)
        return $v;
    }
    return false;
  }
  
  static public function first_key(array $arr) {
    foreach ($arr as $k => $v) return $k;
  }
  
  static public function first(array $arr, $key = null) {
    foreach ($arr as $k => $v) {
      if ($key) return $v[$key];
      return $v;
    }
    return false;
  }
  
  static public function last(array $arr) {
    $vv = false;
    foreach ($arr as $v) $vv = $v;
    return $vv;
  }
  
  static public function last_key(array $arr) {
    $keys = array_keys($arr);
    return $keys[count($keys)-1];
  }
  
  static public function is_empty($value) {
    if (is_array($value)) {
      foreach ($value as &$v) {
        if (is_array($v)) {
          if (!Arr::is_empty($v)) return false; 
        } elseif ($v) return false;
      }
      return true;
    } else {
      return $value ? false : true;
    }
  }
  
  static public function flip2(array $arr) {
    foreach ($arr as $k => $v) {
      foreach ($v as $v2) {
        $arr2[$v2] = $k;
      }
    }
    return $arr2;
  }
  
  /*
  static public function php2js(array $arr, $level = 0) {
    if (! is_array($arr))
      throw new NgnException('$arr not an array');
    if (! $arr[0])
      $hash = true;
    $tab = str_repeat(' ', $level * 2);
    $tab2 = str_repeat(' ', ($level+1) * 2);
    foreach ($arr as $k => $v) {
      if (is_array($v))
        $r[] = $tab2 . ($hash ? ("'$k': " . Arr::php2js($v, $level+1)) : Arr::php2js($v, $level+1));
      else
        $r[] = $tab2 . ($hash ? ("'$k': " . ': ' . Arr::formatValue($v)) : Arr::formatValue($v));
    }
    if ($hash) {
      return "{\n" . implode(",\n", $r) . "\n" . $tab . "}";
    } else {
      return "[\n" . implode(",\n", $r) . "\n" . $tab . "]";
    }
  }
  */
  
  static public function sort_by_order_key(array $arr, $key, $order = SORT_ASC) {
    if (!$arr) return array();
    foreach ($arr as $k => $v) {
      $o[$k] = isset($v[$key]) ? $v[$key] : 0;
    }
    array_multisort($o, $order, $arr);
    return $arr;
  }
  
  static public function quote(array &$arr) {
    array_walk($arr, 'quoting');
    return $arr;
  }
  
  static public function to_obj_prop(array $arr, $obj, array $filter = array()) {
    foreach ($arr as $k => $v) {
      //if (empty($v)) continue;
      if (!empty($filter) and !in_array($k, $filter)) continue;
      if (isset($obj->$k)) $obj->$k = $v;
    }
  }
  
  static public function to_array(array $arr, array &$arr2) {
    foreach ($arr as $k => $v) {
      if (empty($v)) continue;
      if (isset($arr2[$k])) $arr2[$k] = $v; 
    }
  }
  
  static public function remove(&$arr, $_v) {
    foreach ($arr as $k => $v) {
      if ($v == $_v) {
        unset($arr[$k]);
      }
    }
  }
  
  static public function filter_key_in_array(array $arr, array $in) {
    $new = array();
    foreach ($arr as $k => $v)
      if (in_array($k, $in))
        $new[$k] = $v;
    return $new;
  }
  
  static public function filter_array(array $arr, array $in) {
    $new = array();
    foreach ($arr as $v) {
      if (!in_array($v, $in))
        $new[] = $v;
    }
    return $new;
  }
  
  static public function filter_empties(array &$arr, $assoc = true) {
    $new = array();
    if ($assoc) {
      foreach ($arr as $k => $v) {
        if (!Arr::is_empty($v)) {
          $new[$k] = $v;
        }
      }
    } else {
      foreach ($arr as $v)
        if (!Arr::is_empty($v)) $new[] = $v;
    }
    $arr = $new;
  }
  
  static public function filter_empties_strings(array &$arr, $assoc = true) {
    $new = array();
    if ($assoc) {
      foreach ($arr as $k => $v)
        if ($v != '') $new[$k] = $v;
    } else {
      foreach ($arr as $v)
        if ($v != '') $new[] = $v;
    }
    $arr = $new;
  }
  
  static public function filter_empties2(array $arr, $assoc = true) {
    $new = array();
    if ($assoc) {
      foreach ($arr as $k => $v) {
        if (is_array($v)) Arr::filter_empties($v);
        if ($v) $new[$k] = $v;
      }
    } else {
      foreach ($arr as $v) {
        if (is_array($v)) Arr::filter_empties($v);
        if ($v) $new[] = $v;
      }
    }
    return $new;
  }
  
  static public function get_key_by_value(array $arr, $subK, $subV) {
    foreach ($arr as $k => $v)
      if (isset($v[$subK]) and $v[$subK] == $subV)
        return $k;
    return false;
  }
  
  static public function filter_by_keys(array $arr, $keys) {
    $keys = (array)$keys;
    $r = array();
    foreach ($arr as $key => $val) {
      if (in_array($key, $keys))
        $r[$key] = $val;
    }
    return $r;
  }
  
  static public function filterExceptKeys(array $arr, $keys) {
    $keys = (array)$keys;
    $r = array();
    foreach ($arr as $key => $val) {
      if (in_array($key, $keys)) continue;
      $r[$key] = $val;
    }
    return $r;
  }
  
  static public function filter_by_regexp(array $arr, $regexp) {
    $r = array();
    foreach ($arr as $key => $val) {
      if (preg_match($regexp, $val))
        $r[$key] = $val;
    }
    return $r;
  }
  
  static public function filter_by_value(array $arr, $key, $value, $assoc = false, $ignore = false) {
    $r = array();
    foreach ($arr as $k => $v) {
      if ($ignore and !isset($v[$key])) continue;
      if (isset($v[$key]) and $v[$key] == $value) {
        if ($assoc)
          $r[$k] = $v;
        else
          $r[] = $v;
      }
    }
    return $r;
  }
  
  static public function filter_and_replace_by_regexp(array $arr, $regexp) {
    $r = array();
    foreach ($arr as $key => $val) {
      if (preg_match($regexp, $val, $m))
        $r[$key] = $m[1];
    }
    return $r;
  }
  
  static public function filter_func(array $arr, $func, $assoc = true) {
    $r = array();
    if ($assoc) {
      foreach ($arr as $k => $v) if ($func($v)) $r[$k] = $v;
    } else {
      foreach ($arr as $k => $v) if ($func($v)) $r[] = $v;
    }
    return $r;
  }
  
  static public function get_from(array $arr, $n) {
    $arr2 = array();
    if (count($arr) <= $n) return $arr2;
    for ($i=$n; $i<count($arr); $i++)
      $arr2[] = $arr[$i];
    return $arr2;
  }
  
  static public function to_options(array $arr, $key = null) {
    $r = array();
    if ($key) 
      foreach ($arr as $k => $v)
        $r[$k] = $v[$key];
    else {
      foreach ($arr as $v)
        $r[$v] = $v;
    }
     return $r;
  }
  
  static public function to_assoc($arr, $key) {
    $arr2 = array();
    foreach ($arr as $v) $arr2[$v[$key]] = $v;
    return $arr2;
  }
  
  static public function add_number(array &$arr) {
    $n = 1;
    foreach ($arr as $k => $v) {
      $arr[$k]['n'] = $n;
      $n++;
    }
  }
  
  static public function proximity(array $arr, $current, $loop = false) {
    for ($i=0; $i<count($arr); $i++) {
      if ($current == $arr[$i]) {
        $prev = isset($arr[$i-1]) ? $arr[$i-1] : ($loop ? $arr[count($arr)-1] : -1);
        $next = isset($arr[$i+1]) ? $arr[$i+1] : ($loop ? $arr[0] : -1);
      }
    }
    if (!isset($prev)) return false;
    return array($prev, $next);
  }
  
  static public function files_convert(array $files) {
    $r = array();
    foreach ($files as $propName => $v) {
      foreach ($v as $name => $value) {
        $r[$name][$propName] = $value;
      }
    }
    return $r;
  }
  
  static public function str_replace($arr, $search, $replace) {
    foreach ($arr as $k => $v) {
      $arr[$k] = str_replace($search, $replace, $v);
    }
    return $arr;
  }
  
  static protected function js(array $array,
                               $formatValue = true, 
                               $isArray = true,
                               $replaceInnerArraysByArrays = true) {

    $isArray = $replaceInnerArraysByArrays ? true : $isArray;
    
    if ($isArray) {
      $bracketO = '[';
      $bracketC = ']';
    } else {
      $bracketO = '{';
      $bracketC = '}';
    }
    
    $jsarray = $bracketO; 
    $temp = array();
    foreach ($array as $key => $value) { 
      $jskey = $isArray ? '' : "'" . $key . "': "; 
      if (is_array($value)) { 
        $temp[] = $jskey.Arr::js($value, true, $isArray, $replaceInnerArraysByArrays); 
      } else { 
        if (is_numeric($value)) { 
          $jskey .= $value;
        } elseif (is_bool($value)) { 
          $jskey .= ($value ? 'true' : 'false') . ""; 
        } elseif ($value === null) { 
          $jskey .= "null";
        } else {
          if ($formatValue) {
            $jskey .= self::jsString($value);
          } else {
            $jskey .= $value;
          }
        } 
        $temp[] = $jskey; 
      }
    } 
    $jsarray .= implode(', ', $temp);
    $jsarray .= $bracketC;
    return $jsarray; 
  }
  
  static public function jsString($s) {
    return "'".str_replace(
      array("\\", "'", "\r", "\n"),
      array('\\\\', '\\\'', '\r', '\n'),
    $s)."'";
  }
  
  static public function jsObj($array, $formatFirstLevelValue = true) {
    return Arr::js($array, $formatFirstLevelValue, false, false);
  }
  
  static public function jsArr($array, $formatFirstLevelValue = true) {
    return Arr::js($array, $formatFirstLevelValue, true, true); 
  }
  
  static public function jsValue($v) {
    if (is_bool($v)) return $v ? 'true' : 'false';
    elseif ($v === null) return 'null';
    elseif (is_numeric($v)) return $v;
    elseif (is_array($v)) return self::jsArr($v);
    else return self::jsString($v);
  }

  static public function formatValue($v, $stringBools = true) {
    if (is_array($v)) {
      $values = array();
      foreach ($v as $kk => &$vv) {
        $values[] = (is_int($kk) ? $kk : "'$kk'")." => ".Arr::formatValue($vv);
      }
      return 'array('.implode(', ', $values).')';
    } elseif ($v === 'true' or $v === 'false') {
      return $v == 'true' ? ($stringBools ? 'true' : true) : ($stringBools ? 'false' : false);
    } elseif (is_bool($v)) {
      return $v ? ($stringBools ? 'true' : true) : ($stringBools ? 'false' : false);
    } elseif (strlen($v) == strlen((int)$v)) {
      return (int)$v;
    } else {
      return $stringBools ? "'$v'" : $v;
    }
  }

  static public function formatValue2($v) {
    return self::formatValue($v, false);
  }
  
  /**
   * Преобразует строку вида "'asd'" '"asd"' или "false" в соответствующее 
   * значение типа string или boolen  
   *
   * @param   array
   * @return  array
   */
  static public function deformatValue($v) {
    if (($v[0] == "'" and $v[count($v) - 1] == "'") or ($v[0] == '"' and $v[count($v) - 1] == '"'))
      return substr($v, 1, count($v) - 2);
    else {
      if ($v == 'true')
        return true;
      elseif ($v == 'false')
        return false;
      return $v;
    }
  }

  /**
   * Меняет строки в многомерном массиве на интежеры 
   *
   * @param   array
   * @return  array
   */
  static public function transformValue($v) {
    if (is_array($v)) {
      foreach ($v as &$vv)
        $vv = Arr::transformValue($vv);
      return $v;
    } elseif (strlen($v) == strlen((int)$v)) {
      return (int)$v;
    } else {
      return $v;
    }
  }

  static public function checkEmpty(array $arr, $keys, $quitely = false) {
    if (!is_array($keys)) $keys = array($keys);
    foreach ($keys as $k) {
      if (empty($arr[$k])) {
        if ($quitely) return false;
        else throw new NgnException("Key '$k' has empty value in array: ".getPrr($arr));
      }
    }
    return true;
  }
  
  static public function checkNotEmptyAny(array $arr, array $keys) {
    foreach ($keys as $k) {
      if (!empty($arr[$k])) return;
    }
    throw new NgnException("Array has only empty values: ".getPrr($arr));
  }
  
  static public function checkIsset(array $arr, $keys) {
    $keys = (array)$keys;
    foreach ($keys as $k)
      if (!isset($arr[$k]))
        throw new NgnException("Key '$k' does not exists in array: ".getPrr($arr));
  }
  
  static public function explodeCommas($s) {
    $s = explode(',', $s);
    array_walk($s, 'trim');
    return $s;
  }
  
  static public function replaceKey(array $arr, $oldKey, $newKey) {
    $new = array();
    foreach ($arr as $k => $v) {
      if ($k == $oldKey) {
        $k = $newKey;
      }
      $new[$k] = $v;
    }
    return $new;
  }
  
  static public function unsetKey(array $arr, $key) {
    unset($arr[$key]);
    return $arr;
  }
  
  static public function subValueExists(array $arr, $key, $value) {
    foreach ($arr as $v) {
      foreach ($v as $kk => $vv) {
        if ($kk == $key and $vv == $value) return true;
      }
    }
    return false;
  }
  
  static public function splat($v) {
    return is_array($v) ? $v : array($v);
  }
  
  static function diff(array $arr1, array $arr2) {
    $r = array();
    foreach ($arr1 as $v)
      if (!in_array($v, $arr2) and !in_array($v, $r)) $r[] = $v;
    foreach ($arr2 as $v)
      if (!in_array($v, $arr1) and !in_array($v, $r)) $r[] = $v;
    return $r;
  }
  
  static function filter_by_keys2(array $arr, array $keys) {
    $r = array();
    foreach ($arr as $k => $v) {
      $r[$k] = self::filter_by_keys($v, $keys);
    }
    return $r;
  }
  
  static public function replaceObjArrValue($objArr, $key, $value) {
    $objArr[$key] = $value;
    return $objArr;
  }
  
  static function isAssoc(array $arr) {
    return array_keys($arr) !== range(0, count($arr) - 1);
  }
  
  static public function rand(array $arr) {
    return $arr[array_rand($arr)];
  }
  
  static public function unserialize(array $data) {
    foreach ($data as $k => $v) { 
      if (!empty($v) and Misc::unserializeble($v)) {
        $_v = $v;
        $v = unserialize($v);
        if ($v === false) {
          throw new NgnException('Error unserialization $v: "'.print($_v).'"');
        } else {
          $data[$k] = $v;
        }
      }
    }
    return $data;
  }
  
  static public function serialize(array $data) {
    foreach ($data as $k => $v)
      if (is_array($v))
        $data[$k] = serialize($data[$k]);
    return $data;
  }
  
  static public function injectAfter(array $array, $k, $v, $assocKey = false) {
    $r = array();
    if ($assocKey) {
      foreach ($array as $kk => $vv) {
        $r[$kk] = $vv;
        if ($kk == $k) $r[$v[$assocKey]] = $v;
      }
    } else {
      for ($i=0; $i<count($array); $i++) {
        $r[] = $array[$i];
        if ($i == $k) $r[] = $v;
      }
    }
    return $r;
  }
  
  static public function replaceSubValue(array $arr, $key, $find, $replace) {
    foreach ($arr as $k => $v)
      if ($v[$key] == $find) $v[$key] = $replace;
    return $arr;
  }
  
  static public function sortByArray(array $array, array $orderKeys) {
    $ordered = array();
    foreach ($orderKeys as $key) {
      if (array_key_exists($key, $array)) {
        $ordered[$key] = $array[$key];
        unset($array[$key]);
      }
    }
    return $ordered + $array;
  }

}
