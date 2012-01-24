<?php

/*
 * SAPE.ru -- Интеллектуальная система купли-продажи ссылок
 *
 * PHP-клиент, версия 1.0.7 от 16.02.2010
 *
 * По всем вопросам обращайтесь на support@sape.ru
 *
 * Вебмастеры! Не нужно ничего менять в этом файле! Все настройки - через параметры при вызове кода.
 * Читайте: http://help.sape.ru/
 *
 */

// Основной класс, выполняющий всю рутину
class SAPE_base {

  public $_version       = '1.0.7';

  public $_verbose       = false;

  public $_charset       = '';         // http://www.php.net/manual/en/function.iconv.php
  
  public $_sape_charset    = '';

  public $_server_list     = array('dispenser-01.sape.ru', 'dispenser-02.sape.ru');

  public $_cache_lifetime  = 3600;       // Пожалейте наш сервер :о)

  // Если скачать базу ссылок не удалось, то следующая попытка будет через столько секунд
  public $_cache_reloadtime  = 600;

  public $_error       = '';

  public $_host        = '';

  public $_request_uri     = '';     
  
  public $_multi_site    = false;

  public $_fetch_remote_type = '';        // Способ подключения к удалённому серверу [file_get_contents|curl|socket]

  public $_socket_timeout  = 6;         // Сколько ждать ответа

  public $_force_show_code   = false;

  public $_is_our_bot    = false;       // Если наш робот

  public $_debug       = false;

  public $_db_file       = '';        // Путь к файлу с данными

  public function __construct($options = null) {

    // Поехали :o)

    $host = '';

    if (is_array($options)) {
      if (isset($options['host'])) {
        $host = $options['host'];
      }
    } elseif (strlen($options)) {
      $host = $options;
      $options = array();
    } else {
      $options = array();
    }

    // Какой сайт?
    if (strlen($host)) {
      $this->_host = $host;
    } else {
      $this->_host = SITE_DOMAIN;
    }

    $this->_host = preg_replace('/^http:\/\//', '', $this->_host);
    $this->_host = preg_replace('/^www\./', '', $this->_host);

    // Какая страница?
    if (isset($options['request_uri']) && strlen($options['request_uri'])) {
      $this->_request_uri = $options['request_uri'];
    } else {
      $this->_request_uri = $_SERVER['REQUEST_URI'];
    }

    // На случай, если хочется много сайтов в одной папке
    if (isset($options['multi_site']) && $options['multi_site'] == true) {
      $this->_multi_site = true;
    }

    // Сообщать об ошибках
    if (isset($options['verbose']) && $options['verbose'] == true) {
      $this->_verbose = true;
    }

    // Кодировка
    if (isset($options['charset']) && strlen($options['charset'])) {
      $this->_charset = $options['charset'];
    } else {
      $this->_charset = 'windows-1251';
    }

    if (isset($options['fetch_remote_type']) && strlen($options['fetch_remote_type'])) {
      $this->_fetch_remote_type = $options['fetch_remote_type'];
    }

    if (isset($options['socket_timeout']) && is_numeric($options['socket_timeout']) && $options['socket_timeout'] > 0) {
      $this->_socket_timeout = $options['socket_timeout'];
    }

    // Всегда выводить чек-код
    if (isset($options['force_show_code']) && $options['force_show_code'] == true) {
      $this->_force_show_code = true;
    }

    // Выводить информацию о дебаге
    if (isset($options['debug']) && $options['debug'] == true) {
      $this->_debug = true;
    }

    if (!defined('_SAPE_USER')) {
      return $this->raise_error('Не задана константа _SAPE_USER');
    }

    // Определяем наш ли робот
    if (isset($_COOKIE['sape_cookie']) && ($_COOKIE['sape_cookie'] == _SAPE_USER)) {
      $this->_is_our_bot = true;
      if (isset($_COOKIE['sape_debug']) && ($_COOKIE['sape_debug'] == 1)){
        $this->_debug = true;
      }
    } else {
      $this->_is_our_bot = false;
    }
  }


  /*
   * Функция для подключения к удалённому серверу
   */
  public function fetch_remote_file($host, $path) {

    $user_agent = $this->_user_agent.' '.$this->_version;

    @ini_set('allow_url_fopen',      1);
    @ini_set('default_socket_timeout',   $this->_socket_timeout);
    @ini_set('user_agent',         $user_agent);
    if (
      $this->_fetch_remote_type == 'file_get_contents'
      ||
      (
        $this->_fetch_remote_type == ''
        &&
        function_exists('file_get_contents')
        &&
        ini_get('allow_url_fopen') == 1
      )
    ) {
      $this->_fetch_remote_type = 'file_get_contents';
      if ($data = @file_get_contents('http://' . $host . $path)) {
        return $data;
      }

    } elseif (
      $this->_fetch_remote_type == 'curl'
      ||
      (
        $this->_fetch_remote_type == ''
        &&
        function_exists('curl_init')
      )
    ) {
      $this->_fetch_remote_type = 'curl';
      if ($ch = @curl_init()) {

        @curl_setopt($ch, CURLOPT_URL,        'http://' . $host . $path);
        @curl_setopt($ch, CURLOPT_HEADER,       false);
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER,   true);
        @curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,   $this->_socket_timeout);
        @curl_setopt($ch, CURLOPT_USERAGENT,    $user_agent);

        if ($data = @curl_exec($ch)) {
          return $data;
        }

        @curl_close($ch);
      }

    } else {
      $this->_fetch_remote_type = 'socket';
      $buff = '';
      $fp = @fsockopen($host, 80, $errno, $errstr, $this->_socket_timeout);
      if ($fp) {
        @fputs($fp, "GET {$path} HTTP/1.0\r\nHost: {$host}\r\n");
        @fputs($fp, "User-Agent: {$user_agent}\r\n\r\n");
        while (!@feof($fp)) {
          $buff .= @fgets($fp, 128);
        }
        @fclose($fp);

        $page = explode("\r\n\r\n", $buff);

        return $page[1];
      }

    }

    return $this->raise_error('Не могу подключиться к серверу: ' . $host . $path.', type: '.$this->_fetch_remote_type);
  }

  /*
   * Функция чтения из локального файла
   */
  public function _read($filename) {
    
    $fp = @fopen($filename, 'rb');
    @flock($fp, LOCK_SH);
    if ($fp) {
      clearstatcache();
      $length = @filesize($filename);
      if ($length) {
        $data = @fread($fp, $length);
      } else {
        $data = '';
      }
      @flock($fp, LOCK_UN);
      @fclose($fp);
      return $data;
    }

    return $this->raise_error('Не могу считать данные из файла: ' . $filename);
  }

  /*
   * Функция записи в локальный файл
   */
  public function _write($filename, $data) {

    $fp = @fopen($filename, 'ab');
    if ($fp) {
      if (flock($fp, LOCK_EX|LOCK_NB)) {
  $length = strlen($data);
  ftruncate($fp, 0);
  @fwrite($fp, $data, $length);
  @flock($fp, LOCK_UN);
  @fclose($fp);

  if (md5($this->_read($filename)) != md5($data)) {
    @unlink($filename);
    return $this->raise_error('Нарушена целостность данных при записи в файл: ' . $filename);
  }
    } else {
  return false;
    }

      return true;
    }

    return $this->raise_error('Не могу записать данные в файл: ' . $filename);
  }

  /*
   * Функция обработки ошибок
   */
  public function raise_error($e) {

    $this->_error = '<p style="color: red; font-weight: bold;">SAPE ERROR: ' . $e . '</p>';

    if ($this->_verbose == true) {
      print $this->_error;
    }

    return false;
  }

  public function load_data() {
    $this->_db_file = $this->_get_db_file();

    if (!is_file($this->_db_file)) {
      // Пытаемся создать файл.
      if (@touch($this->_db_file)) {
        @chmod($this->_db_file, 0666);  // Права доступа
      } else {
        return $this->raise_error('Нет файла ' . $this->_db_file . '. Создать не удалось. Выставите права 777 на папку.');
      }
    }

    if (!is_writable($this->_db_file)) {
      return $this->raise_error('Нет доступа на запись к файлу: ' . $this->_db_file . '! Выставите права 777 на папку.');
    }

    @clearstatcache();

  $data = $this->_read($this->_db_file);
    if (
      !$this->_is_our_bot
      &&
      (
        filemtime($this->_db_file) < (time()-$this->_cache_lifetime)
        ||
        filesize($this->_db_file) == 0
  ||
  @unserialize($data) == false
      )
    ) {
      // Чтобы не повесить площадку клиента и чтобы не было одновременных запросов
      //$r = @touch($this->_db_file, (time() - $this->_cache_lifetime + $this->_cache_reloadtime));
      
      $path = $this->_get_dispenser_path();
      if (strlen($this->_charset)) {
        $path .= '&charset=' . $this->_charset;
      }

      foreach ($this->_server_list as $i => $server){
        if ($data = $this->fetch_remote_file($server, $path)) {
          if (substr($data, 0, 12) == 'FATAL ERROR:') {
            $this->raise_error($data);
          } else {
            // [псевдо]проверка целостности:
            $hash = @unserialize($data);
            if ($hash != false) {
              // попытаемся записать кодировку в кеш
              $hash['__sape_charset__'] = $this->_charset;
              $hash['__last_update__'] = time();
              $hash['__multi_site__'] =  $this->_multi_site;
              $hash['__fetch_remote_type__'] = $this->_fetch_remote_type;
              $hash['__php_version__'] = phpversion();
              $hash['__server_software__'] = $_SERVER['SERVER_SOFTWARE'];
              
              $data_new = @serialize($hash);
              if ($data_new) {
                $data = $data_new;
              }
              
              $this->_write($this->_db_file, $data);
              break;
            }
          }
        }
      }
    }

    // Убиваем PHPSESSID
    if (strlen(session_id())) {
      $session = session_name() . '=' . session_id();
      $this->_request_uri = str_replace(array('?'.$session,'&'.$session), '', $this->_request_uri);
    }

    $this->set_data(@unserialize($data));    
  }
}