<?php

class Html {


  /**
   * Добавляет к тэгу строку, в которой можно использовать ключевые символы $1
   * для вставки значения "name" этого тэга и $2 для вставки значения параметра "value".
   *
   * @param   string  Исходный HTML код
   * @param   string  Значение параметра "name" тэга
   * @param   string  HTML код добавляющийся вконце тэга, если значение есть)
   * @return  string  HTML код добавляющийся вконце тэга, если значение пустое)
   */
  static public function inputAppend($html, $name, $appender, $emptyAppender = '') {
    $name = str_replace('[', '\\[', $name);
    $name = str_replace(']', '\\]', $name);
    // Заменяем input'ы с заполненным значением
    $html = preg_replace(
      array(
        '/<[^>]*name="('.$name.')"[^>]*value="([^>"]+)"[^>]*>/um',
      ),
      '$0'.$appender,
      $html
    );
    
    // Заменяем пустые input'ы
    $html = preg_replace(
      array(
        '/<[^>]*name="('.$name.')"[^>]*value=""[^>]*>/um',
      ),
      '$0'.$emptyAppender,
      $html
    );
    
    return $html;
  }
  
  /**
   * Убирает тэг из HTML-кода
   *
   * @param   string  HTML
   * @param   string  Имя тэга
   * @param   array   Необходимый параметр тэга
   *                  Пример: array('img', './u/img.png')
   * @return  string  HTML
   */
  static public function removeTag($html, $tagName, $param = null) {
    if ($param) {
      $regex = '/<(?='.$tagName.')([^>]+)'.$param[0].'=("|\'|)'.$param[1].'("|\'|)([^>]*)>/';
    } else
      $regex = '/<(?='.$tagName.')([^>]*)>/';
    return preg_replace($regex, '', $html);
  }
  
  static public function replaceParam2($html, $tag, $paramName, $paramOldValue, $paramNewValue) {
    $paramOldValue = str_replace('.', '\.', $paramOldValue);
    $paramOldValue = str_replace('/', '\/', $paramOldValue);
    return preg_replace(
      '/(<'.$tag.')([^>]+)('.$paramName.'=)("|\'|)'.$paramOldValue.'("|\'|)([^>]*>)/',
      '$1$2$3"'.$paramNewValue.'"$6', $html);
  }
  
  static public function addParam($html, $name, $value, $tags = null) {
    if (is_array($tags)) $tags = implode('|', $tags); 
    return preg_replace('/<(?!\/)(?='.$tags.')([^>]+)>/', '<$1 '.$name.'="'.$value.'">', $html);
  }
  
  
  // -------------------------------------------------------------------------------------------
  static public function inputReplace($html, $name, $replacer) {
    $name = str_replace('[', '\[', $name);
    $name = str_replace(']', '\]', $name);
    return preg_replace('/<[^>]*name="'.$name.'"[^>]*>/um', $replacer, $html);
  }
  
  static public function inputAddClass($html, $types, $class) {
    return preg_replace(
      '/(<input(?:[^>]*)type="(?:'.implode('|',$types).')")((?:[^>]*)>)/um',
      '$1 class="'.$class.'"$2',
      $html
    );
  }

  static public function inputRevalue($html, $name, $value) {
    return preg_replace(
      '/<([^>]*)name="'.$name.'"([^>]*)value="([^>]*)"([^>]*)>/um',
      '<$1name="'.$name.'"$2value="'.$value.'"$4>',
      $html
    );
  }
  
  static public function inputIsValue($html, $name) {
    return preg_match(
      '/<([^>]*)name="'.$name.'"([^>]*)value="([^>]+)"([^>]*)>/um',
      $html
    );    
  }
  
  static public function inputPrepend($html, $name, $prepender) {
    return preg_replace(
      '/<[^>]*name="('.$name.')"[^>]*value="([^>"]+)"[^>]*>/um',
      $prepender.'$0',
      $html
      
    );
  }
  
  static public function inputNameToArray($html, $arrayName) {
    return preg_replace('/(<[^>]*name=")([^>^"]*)("[^>]*>)/um', '$1'.$arrayName.'[$2]$3', $html);
  }

  static public function inputExists($html, $name) {
    return preg_match('/<[^>]*name="'.$name.'"[^>]*>/um', $html);
  }
  
  static public function replaceParam($html, $name, $value, $tags = null) {
    if (is_array($tags)) $tags = implode('|', $tags); 
    return preg_replace(
      '/<(?='.$tags.')(?!\/)([^>]+)'.$name.'=("|\'|)([^>^"]*)("|\'|)([^>]*)>/',
      '<'.$tag.'$1'.$name.'=$2'.$value.'$4$5>',
      $html
    );
  }
  
  static public function getTagNames($html, $tag, $type = null) {
    if ($type) $typeCond = 'type="'.$type.'"[^>]*';
    preg_match_all(
      '/<'.$tag.'[^>]*'.$typeCond.'name="([a-zA-Z_]*)"[^>]*>/um', 
      $html, $m
    );
    return $m[1] ? $m[1] : array();
  }

  static public function getInputDataNames($html) {
    $names = array();
    $names = Arr::append($names, self::getTagNames($html, 'textarea'));
    $names = Arr::append($names, self::getTagNames($html, 'input', 'text'));
    $names = Arr::append($names, self::getTagNames($html, 'input', 'password'));
    $names = Arr::append($names, self::getTagNames($html, 'input', 'radio'));
    $names = Arr::append($names, self::getTagNames($html, 'input', 'file'));
    $names = Arr::append($names, self::getTagNames($html, 'input', 'button'));
    $names = Arr::append($names, self::getTagNames($html, 'input', 'submit'));
    $names = Arr::append($names, self::getTagNames($html, 'select'));
    return $names;  
  }

  static public function getInputValue($html, $name) {
    preg_match('/<[^>]*name="'.$name.'"[^>]*value="([^>^"]*)"[^>]*>/um', $html, $m);
    return $m[1];
  }

  static public function emptyHtml($html) {
    $html = htmlspecialchars_decode($html);
    $html = strip_tags($html);
    $html = str_replace(array(' ', " ", "\n", "\r"), '', $html);
    return preg_match('/[a-zA-ZА-Яа-я\-.,_]+/', $html) ? false : true;
  }
  
  
  /**
   * Enter description here ...
   * @param unknown_type $name
   * @param unknown_type $options
   * @param unknown_type $default
   * @param array  (tagId, class, noSelectTag, defaultCaption)
   */
  static public function select($name, $options, $default = null, array $opts = array()) {
    if (empty($opts['noSelectTag'])) {
      $html = "<select name=\"$name\"".
              (!empty($opts['tagId']) ? ' id="'.$opts['tagId'].'"' : "").
              (!empty($opts['tagId']) ? ' class="'.$opts['class'].'"' : "").'>';
    } else {
      $html = '';
    }
    if (!empty($opts['defaultCaption'])) $default = $opts['defaultCaption'];
    foreach ($options as $key => $val) {
      $k = $key;
      if (!empty($opts['defaultCaption'])) $k = $val;
      $html .= "\t<option value=\"".$key."\"".($k == $default ? ' selected' : '').">".
               $val."</option>\r\n";
    }
    if (empty($opts['noSelectTag'])) $html .= "</select>\r\n";
    return $html;
  }
  
  /**
   * Возвращает имена полей по типам
   *
   * @param   array   Полей с полной информацией о них
   * @param   array   Типы полей
   * @return  array   Имена
   */
  static public function getFieldNames($fields, $types) {
    $names = array();
    foreach ($fields as $v) {
      if (in_array($v['type'], $types)) {
        $names[] = $v['name'];
      }
    }
    return $names;
  }
  
  static public function userTag(&$d) {
    return '<a href="'.Tt::getUserPath($d['userId'] ? $d['userId'] : $d['id']).'">'.$d['login'].'</a>';
  }
  
  static public function replaceFileInput(&$d, $type, $title1) {
    if (!$d['fields']) throw new NgnException("\$d['fields'] not defined");
    // Для всех файловых полей добавляем линк на удаление файла
    foreach ($d['fields'] as $v) {
      if ($v['type'] != $type) continue;
      $name = $v['name'];
      $deleteBtn = $v['required'] ? '' :
        '<a href="'.Tt::getPath().'?a=deleteFile'.
        (isset($d['itemId']) ? '&itemId='.$d['itemId'] : '').
        '&fieldName=$1" '.
        'target="_blank" onclick="if (confirm(\'Вы уверены?\')) window.location=this.href; '.
        'return false;" class="ddelete">Удалить</a>';
      $d['form'] = Html::inputAppend($d['form'], $name,
        '<div class="iconsSet">'.
        '<a href="'.Tt::getPath(0).UPLOAD_DIR.'/$2" target="_blank" class="'.$type.'"><i></i>'.$title1.'</a>'.
        $deleteBtn.
        '<div class="clear"><!-- --></div></div>'
      );
    }
  }
  
  static public function replaceImageInput(&$d, $type, $title1) {
  }
  
  /* Укорачивает строку в LABEL-ах */
  static public function cutLabel($html, $n = 20) {
    return preg_replace_callback(
      '/(<label for="rub[^>]*)(>\s*<input[^>]*>\s+)(.*)(<\/label>)/U',
      create_function(
        '$m',
        '
        return mb_strlen($m[3], CHARSET) > '.$n.' ?
          $m[1].$m[2].\'<span class="tooltip" title="\'.$m[3].\'">\'.Misc::cut($m[3], '.$n.').\'</span>\'.$m[4] :
          $m[1].$m[2].$m[3].$m[4];
        '
      ),
      $html
    );
  }
  
  static public function getInnerContent($content, $bef, $aft = '') { 
    $len = strlen($bef); 
    $posBef = strpos($content, $bef); 
    if ($posBef === false) return ''; 
    $posBef += $len;
    if (empty($aft)) {
      // try to search up to the end of line 
      $posAft = strpos($content, "\n", $posBef); 
      if ($posAft === false)
        $posAft = strpos($content, "\r\n", $posBef); 
    } 
    else 
      $posAft = strpos($content, $aft, $posBef); 
     
    if($posAft !== false) 
      $rez = substr($content, $posBef, $posAft-$posBef); 
    else 
      $rez = substr($content, $posBef); 
    return $rez; 
  }
  
  static public function subDomainLinks($html, $subdomain) {
    return preg_replace(
      '/(href|src|action)="(?!\/\/|http:\/\/)\/*([^"]+)/',
      '$1="//'.$subdomain.'.'.SITE_DOMAIN.'/$2',
      $html
    );
  }
  
  static public function baseDomainLinks($html) {
    return preg_replace(
      '/(href|src|action)="(?!\/\/|http:\/\/)\/*([^"]+)/',
      '$1="//'.SITE_DOMAIN.'/$2',
      $html
    );
  }

}
