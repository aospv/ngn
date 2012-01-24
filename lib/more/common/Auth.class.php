<?php

class Auth {
  
  static $expires = 30000000;
  
  /**
   * Не сохранять пароль (авторизация только для текущей сессии)
   *
   * @var bool
   */
  static $doNotSavePass;
  
  static $loginFieldName = 'authLogin';
  
  static $passFieldName = 'authPass';
  
  static $errors;
  
  static $auth;
  
  /**
   * Метод авторизации cookie/session
   *
   * @var string
   */
  static $method = 'session';
  
  const ERROR_AUTH_NO_LOGIN = 1;
  
  const ERROR_AUTH_WRONG_PASS = 2;
  
  const ERROR_AUTH_USER_NOT_ACTIVE = 3;
  
  const ERROR_EMPTY_LOGIN_OR_PASS = 4;
  
  static $errorsText = array(
    self::ERROR_AUTH_NO_LOGIN => 'Пользователь с таким логином не зарегистрирован',
    self::ERROR_AUTH_WRONG_PASS => 'Неверный пароль',
    self::ERROR_AUTH_USER_NOT_ACTIVE => 'Пользователь не активирован',
    self::ERROR_EMPTY_LOGIN_OR_PASS => 'Пустой логин или пароль'
  );
  
  static public function setMethod($method) {
    self::$method = $method;
  } 
  
  static public function cryptPass($pass) {
  return md5(md5(md5($pass)));
    return $pass;
  }
  
  /**
   * Возвращаеммый этой функцией массив будет сохранен в сессию
   *
   * @param   string  Логин
   * @return  array
   */
  static function getUsersData($login) {
    return db()->select(
      'SELECT id, login, pass, active, userDataPageId FROM users WHERE login=?',
      $login);
  }

  /**
   * Проверяет логин-закриптованый пароль в БД
   *
   * @param   string  Логин
   * @param   string  Закриптованиый пароль
   * @return  bool
   */
  static function checkLoginPass($login, $encryptedPass) {
    if (!$usersData = self::getUsersData($login)) {
      // Если не было найдено ниодного пользователя
      self::error(self::ERROR_AUTH_NO_LOGIN);
      return false;
    }
    // Перебираем всех пользователей по этому логину
    foreach ($usersData as $userData) {
      if ($userData['pass'] == $encryptedPass) {
        if ($userData['active'] == 1) {
          return $userData;
        }
        else {
          self::error(self::ERROR_AUTH_USER_NOT_ACTIVE);
          return false;
        }
      } else {
        $wrongPass = true;
      }
    }
    // Если для всех перебраных пользователей пароль неверен
    if ($wrongPass) {
      self::error(self::ERROR_AUTH_WRONG_PASS);
      return false;
    }
  }
  
  static function error($code) {
    self::$errors[] = array(
      'code' => $code,
      'text' => isset(self::$errorsText[$code]) ? self::$errorsText[$code] : 'unknown error with code '.$code
    );
  } 
  
  
  /**
   * Кол-во раз, которое проводилась авторизация
   *
   * @var integer
   */
  static $n=0;

  /**
   * Проверяет пару логин-закриптованый пароль, устанавливает cookie, если 
   * прошла проверка, заполняет глобальный массив $_AUTH данными текущего 
   * авторизованого пользователя
   *
   * @param   string  Логин
   * @param   string  Закриптованиый пароль
   * @param   bool    Если авторизация происходит после начала вывода
   * @return  bool
   */
  static function login($login, $encryptedPass, $afterOutput = false) {
    self::$n++;
    if (($result = self::checkLoginPass($login, $encryptedPass)) !== false) {
      if (!$afterOutput) {
        self::save($result);
      }
      return $result;
    } else {
      return false;
    }
  }
  
  static private function save(&$data) {
    if (self::$method == 'cookie') {
      self::saveCookie($data);
    } else {
      self::saveSession($data);
    }
  }
  
  static function pack($data) {
    foreach ($data as $k => $v) {
      $d[] = $k.'---===---'.$v;
    }
    return implode('|||===|||', $d);
  }
  static function unpack($data) {
    $data = explode('|||===|||', $data);
    foreach ($data as $v) {
      list($k, $v) = explode('---===---', $v);
      $d[$k] = $v;
    }
    return $d;
  }
  
  static private function saveCookie($data) {
    $data = self::pack($data);
    str_replace('"', '\"', $data);
    if (self::$expires) {
      setcookie('auth', $data, time() + self::$expires, '/');
    } else {
      setcookie('auth', $data);
    }
    $_COOKIE['auth'] = $data;
  }
  
  static private function saveSession($data) {
    Session::init();
    $_SESSION['auth'] = $data;
  }
  
  static function relogin() {
    if (!$login = self::get('login')) return false;
    return self::loginByLogin($login);
  }
  
  static function loginByLogin($login) {
    self::$auth = null;
    return self::login(
      $login,
      db()->selectCell('SELECT pass FROM users WHERE login=?', $login)
    );
  }  
  
  static function logout() {
    if (self::$method == 'cookie') {
      if (self::$expires) {
        setcookie('auth', '', time() + self::$expires, '/');
      } else {
        setcookie('auth', '');
      }
      $_COOKIE['auth'] = null;
    } else {
      Session::init();
      $_SESSION['auth'] = null;
    }
  }

  static function clear() {
    if (self::$method == 'cookie') {
      if (self::$expires) {
        setcookie('auth', '', time() + self::$expires, '/');
      } else {
        setcookie('auth', '');
      }
      $_COOKIE['auth'] = null;
    } else {
      Session::init();
      $_SESSION = null;
      Session::delete();
      foreach ($_COOKIE as $k => $v) {
        setcookie($k, '', time() + self::$expires, '/', SITE_DOMAIN);
      }
    }
  }  

  /**
   * Производит авторизацию по данным из cookie
   *
   * @return bool
   */
  static private function loginByCookie() {
    if (isset($_COOKIE['auth'])) {
      $_COOKIE['auth'] = self::unpack($_COOKIE['auth']);
      return self::login($_COOKIE['auth']['login'], $_COOKIE['auth']['pass']);
    } else {
      return false;
    }
  }
  
  static private function loginBySession() {
    return isset($_SESSION['auth']) ? $_SESSION['auth'] : null;
  }
  
  static public $postAuth = false;
  
  /**
   * Производит авторизацию по данным из поста
   *
   * @return bool
   */
  static function loginByPost($login = null, $pass = null) {
    if (!$login and isset($_POST[self::$loginFieldName])) $login = $_POST[self::$loginFieldName];
    if (!$pass and isset($_POST[self::$passFieldName])) $pass = $_POST[self::$passFieldName];
    if (!empty($login) and !empty($pass)) {
      if (!$login or !$pass) {
        self::error(self::ERROR_EMPTY_LOGIN_OR_PASS);
        return false;
      }
      $r = self::login($login, self::cryptPass($pass));
      if ($r)	self::$postAuth = true;
      return $r;
    } else {
      return false;
    }
  }

  /**
   * Производит авторизацию по данным, отосланным с формы авторизации
   *
   * @return bool
   */
  static function loginPage() {
    if (!$r = self::loginByPost()) {
      if (self::$method == 'cookie') {
        return self::loginByCookie();
      } else {
        return self::loginBySession();
      }      
    }
    return $r;
  }
  
  static function setAuth() {
    if (self::$auth) return self::$auth;
    self::$expires = self::$doNotSavePass ? 0 : 60*60*24*10;
    if (($auth = self::loginPage())) {
      if (isset($auth['admin'])) {
        $auth['moder'] = true;
      } else {
        //include_file('includes/Moder.class.php');
        //$auth['moder'] = Moder::canEdit($_id, $id2);
      }
    }
    if (self::$errors[0]) {
      $auth['msg'] = self::$errors[0]['text'];
      $auth['errors'] = self::$errors;
    }
    self::$auth = $auth;
    return $auth;    
  }
  
  static function check() {
    self::setAuth();
    return self::$errors ? false : self::$auth ? true : false;
  }
    
  static function get($param) {
    if (!$param) throw new NgnException('Use getAll');
    $auth = self::setAuth();
    return isset($auth[$param]) ? $auth[$param] : null;
  }
  
  static function getAll() {
    return self::setAuth();
  }
    
}
