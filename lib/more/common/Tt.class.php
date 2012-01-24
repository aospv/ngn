<?php

/**
 * Функции работы с html/php шаблонами через ф-ю require
 */
class Tt {
  
  /**
   * Выводит шаблон
   *
   * @param string $path
   * @param string $data
   */
  static public function tpl($path, $d = null, $quietly = false) {
    if (($tplPath = self::exists($path)) !== false) {
      $clearTplPath = preg_replace('/^(.*)\/tpl\/(.*).php$/U', '$2', $tplPath);
      $body1 = "Begin Template \"$clearTplPath\"";
      $body2 = "End Template \"$clearTplPath\"";
      if (isset($_REQUEST['debugTpl'])) {
        $openCommentBegin = '<div style="border: 1px solid #077F00; padding: 3px; margin: 2px;">';
        $openCommentEnd = '';
        $closeCommentBegin = '';
        $closeCommentEnd = '</div>';
        $body1 = '<small style="color:#077F00;">Begin Template «<b>'.$clearTplPath.'</b>»</small>';
        $body2 = '<small style="color:#077F00;">End Template «<b>'.$clearTplPath.'</b>»</small>';
      } elseif (strstr($tplPath, '.js.php')) {
        $openCommentBegin = $closeCommentBegin = '/* ';
        $openCommentEnd = $closeCommentEnd = ' */'; 
      } else {
        $openCommentBegin = $closeCommentBegin = '<!-- ';
        $openCommentEnd = $closeCommentEnd = ' -->'; 
      }
      if (TEMPLATE_DEBUG === true)
        print "\n".$openCommentBegin.$body1.$openCommentEnd."\n";
      if (Err::$showNotices) {
        Err::noticeSwitch(false);
        $notices = true;
      }
      require $tplPath;
      if (isset($notices)) Err::noticeSwitchBefore();
      if (TEMPLATE_DEBUG === true)
        print "\n".$closeCommentBegin.$body2.$closeCommentEnd."\n";
    }
    elseif (!$quietly) {
      throw new NgnException("Template '$path' not found.");
    }
  }
    
  static public function getTpl($path, $d = null) {
    ob_start();
    self::tpl($path, $d);
    $c = ob_get_contents();
    ob_end_clean();
    return $c;
  }

  /*
  static public function links($links, $tpl = 'common/menu') {
    if (!$links) return;
    Tt::tpl($tpl, array('items' => $links, 'curLink' => $_SERVER['REQUEST_URI']));
  }

  static public function baseTplExists($path) {
    if (file_exists(SITE_BASE_TPL_PATH.'/'.$path.'.php')) return SITE_BASE_TPL_PATH.$path.'.php';
    return false;
  }

  function url($params = null, $firstSlash = true) {  
    static $urls;
    $urls = array();
    if (!$params and !$params = $GLOBALS['_PARAMS']) {
      return ($firstSlash ? '/' : '');
    }
    if (is_array($params)) return ($firstSlash ? '/' : '').implode('/', $params);
    else return ($firstSlash ? '/' : '').$params;
  }

  *   */
  
  /**
   * Проверяет существует ли шаблон с указанным путём и если он 
   * существует возвращает его путь, если нет - false.
   *
   * @param   string  Путь до шаблона
   * @return  mixed
   */
  static public function exists($path) {
    // В первую очередь проверяем наличие шаблона в папке сайта
    if (file_exists(SITE_TPL_PATH.'/'.$path.'.php')) {
      return SITE_TPL_PATH.'/'.$path.'.php';
    // Во вторую - шаблоны тема. Тема может быть не определена
    } elseif (defined('STM_TPL_PATH') and file_exists(STM_TPL_PATH.$path.'.php')) {
      return STM_TPL_PATH.'/'.$path.'.php';
    // В третью - в папке Темы если она определена
    } elseif (file_exists(SITE_SET_TPL_PATH.'/'.$path.'.php')) {
      return SITE_SET_TPL_PATH.'/'.$path.'.php';
    // В последнюю - в папке базовых шаблонов
    } elseif (file_exists(SITE_BASE_TPL_PATH.'/'.$path.'.php')) {
      return SITE_BASE_TPL_PATH.'/'.$path.'.php';
    } elseif (file_exists(TPL_PATH.'/'.$path.'.php')) {
      return TPL_PATH.'/'.$path.'.php';
    }
    else return false;
  }
  
  static public function directTpl($tplPath, $d) {
    include TPL_PATH.'/'.$tplPath.'.php';
  }
  
  /**
   * @var Req
   */
  static protected $oReq;
  
  /**
   * Возвращает путь до текущего раздела без учета QUERY_STRING.
   * Так же можно указать какое количество частей пути нужно получить.
   * Частью пути называется строка отделённая слэшем.
   * Пример:
   * Если текщий URL: http://site.com/path.to/the/page
   * Tt::getPath(2) вернёт строку '/path.to/the'
   *
   * @param   string  Кол-во частей пути, которое необходимо получить
   * @return  Путь до страницы
   */
  static public function getPath($paramsN = null) {
    if (!isset(self::$oReq)) {
      self::$oReq = $oReq = O::get('Req');
    }
    if ($paramsN === 0) return self::$oReq->getBase();
    if ($paramsN !== null) {
      for ($i = 0; $i < $paramsN; $i++)
        $params2[] = isset(self::$oReq->params[$i]) ? self::$oReq->params[$i] : 0;
      return self::$oReq->getBase().implode('/', $params2);
    }
    return '/'.self::$oReq->initPath;
  }
  
  static public function getPathLast($n) {
    $params = O::get('Req')->params;
    $s = '';
    for ($i=count($params)-$n; $i<count($params); $i++)
      $s .= '/'.$params[$i];
    return $s;
  }
  
  static public function getPathWithoutOrder($path, $newParam = null) {
    return self::getPathReplaceFilter($path, 'oa?', $newParam);
  }
  
  static public function getPathWithoutDate($path, $newParam = null) {
    return self::getPathReplaceFilter($path, 'd', $newParam);
  }
  
  static public function getPathReplaceFilter($path, $filter, $newParam = null) {
    $regex = '/(.*)(\/)('.$filter.'\.[a-zA-Z0-9.-]+)(.*)/';
    if (preg_match($regex, $path))
      return preg_replace($regex, '$1'.($newParam ? '$2'.$newParam : '').'$4', $path);
    else
      return $path.($newParam ? '/'.$newParam : '');
  }
  
  static public function getPathRoot() {
    return ($p = Tt::getPath(0)) ? $p : '/';
  }

  /**
   * Возвращает текущий адрес сайта вместе с 'http://'
   *
   * @return  string
   */
  static public function getHostPath() {
    return 'http://'.SITE_DOMAIN;
  }

  /**
   * Возвращает путь до страницы пользователя
   *
   * @param   integer   ID пользователя
   * @return  string    Путь до страницы пользователя
   */
  static public function getUserPath($userId, $quitely = false) {
    if ($quitely) {
      if (($path = Tt::getControllerPath('userData', true)) != '') {
        return '//'.SITE_DOMAIN.'/'.$path.'/'.$userId;
      }
      return false;
    } else {
      return '//'.SITE_DOMAIN.'/'.Tt::getControllerPath('userData').'/'.$userId;
    }
  }
  
  static public function getUserTag($userId, $login, $tpl = '`<a href="`.Tt::getUserPath($id).`">`.$login.`</a>`') {
    if (!PageControllersCore::exists('userData'))
      return '<span class="user">'.$login.'</span>';
    else
      return St::dddd($tpl, array(
        'id' => $userId,
        'login' => $login
      ));
  }
  
  static public function getUserTag2(array $user) {
    return self::getUserTag($user['id'], $user['login']);
  }
  
  static $paths;
  
  static public function getControllerPath($controller, $quietly = false) {
    return PageControllersCore::getControllerPath($controller, $quietly);
  }
  
  static public function getStrControllerPath($controller, $strName, $quietly = false) {
    static $paths;
    if (isset($paths[$controller.$strName])) return $paths[$controller.$strName];
    $path = db()->selectCell(
      "SELECT path FROM pages WHERE controller=? AND strName=? ORDER BY id LIMIT 1",
      $controller, $strName);
    if (!$path) {
      if (!$quietly)
        Err::warning("Page with controller '$controller' not found");
      return '';
    }
    $paths[$controller.$strName] = $path;
    return $paths[$controller.$strName];
  }
  
  /**
   * Возвращает URL с исключенными из него параметрами
   *
   * @param string  URL
   * @param array   Параметры для исключения
   */
  static public function getUrlDeletedParams($url, $params) {
    $parts = parse_url($url);
    parse_str($parts['query'], $out);
    foreach ($out as $k => $v) if (!in_array($k, $params)) $newParams[$k] = $v;
    return isset($newParams) ? $parts['path'].'?'.implode('&', $newParams) : $parts['path'];
  }
  
  /**
   * Склеивает массив в строку с разделителями, помещая при этом значения
   * массива в шаблон.
   *
   * @param   array   Массив с перечислением
   * @param   string  Разделитель
   * @param   string  Шаблон
   * @param   string  Ключ необходим в том случае, если элементом массива является массив
   *                  Ключем в этом случае будет являтся ключ того элемента этого подмассива, 
   *                  который необходимо использовать для склеивания
   * @return  strgin  Склеенная по шаблону строка
   */
  static public function enum($arr, $glue = ', ', $tpl = '$v', $key = null) {
    if (empty($arr) or !is_array($arr)) return '';
    foreach ($arr as $k => $v) {
      if ($key) $v = $v[$key];
      $results[] = St::dddd($tpl, array('k' => $k, 'v' => $v));
      
    }
    return implode($glue, $results);
  }
  
  static public function enumPrefix(array $arr, $glue = ', ', $tpl = '$v', $prefix = '', $postfix = '', $key = null) {
    if (empty($arr) or !is_array($arr)) return '';
    return $prefix.self::enum($arr, $glue, $tpl, $key).$postfix;
  }
  
  static public function enumInlineStyles($arr) {
  //static public function enumInlineStyles(array $arr) {
    if (empty($arr)) return '';
    return self::enumPrefix($arr, '; ', '$k.`: `.$v', ' style="', '"');
  }
  
  /**
   * Тоже самое, что и Tt::enum(), только с измененныым порядком параметров
   *
   * @param   array   Массив с перечислением
   * @param   string  Ключ необходим в том случае, если элементом массива является массив
   *                  Ключем в этом случае будет являтся ключ того элемента этого подмассива, 
   *                  который необходимо использовать для склеивания
   * @param   string  Разделитель
   * @param   string  Шаблон
   * @return  strgin  Склеенная по шаблону строка
   */
  static public function enumK($arr, $key, $glue = ', ', $tpl = '$v') {
    return Tt::enum($arr, $glue, $tpl, $key);
  }
  
  static public function enumDddd($arr, $tpl, $glue = ', ') {
    if (!is_array($arr)) return '';
    foreach ($arr as $v)
      $results[] = St::dddd($tpl, $v);
    return isset($results) ? implode($glue, $results) : '';
  }
  
  static public function enumSsss($arr, $tpl, $glue = ', ') {
    if (!is_array($arr)) return '';
    foreach ($arr as $v)
      $results[] = St::ssss($tpl, $v);
    return isset($results) ? implode($glue, $results) : '';
  }
  
  static public function enumSsss2(array $arr, $tpl, $glue = ', ') {
    foreach ($arr as $k => $v)
      $results[] = St::ssss($tpl, array('k' => $k, 'v' => $v));
    return isset($results) ? implode($glue, $results) : '';
  }
  
  static public function getDbTree(
  $tree, $tplNode, $tplLeaf = '', 
  $tplNodesBegin = '', $tplNodesEnd = '', $extData = null) {
    if (!$tree) return false;
    $o = new DbTreeTpl();
    $o->setNodes($tree);
    if ($extData) $o->setExtData($extData);
    if (!$tplLeaf) {
      $o->setTpl($tplNode);
    } else {
      $o->setNodeTpl($tplNode);
      $o->setLeafTpl($tplLeaf);
      $o->setNodesBeginTpl($tplNodesBegin);
      $o->setNodesEndTpl($tplNodesEnd);
    }
    return $o->html();
  }
  
  static function hasBlocks($action) {
    return !in_array($action, array('new', 'edit', 'complete'));
  }
  
  static function tagParams(array $params) {
    $s = '';
    foreach ($params as $k => $v) $s .= ' '.$k.'="'.$v.'"';
    return $s;
  }
  
  static function httpLink($url) {
    return '<a href="http://'.$url.'" target="_blank">'.$url.'</a>';
  }
  
}
