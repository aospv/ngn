<?php

class SAPE_client extends SAPE_base {

  public $_links_delimiter = '';
  public $_links = array();
  public $_links_page = array();
  public $_user_agent = 'SAPE_Client PHP';

  public function __construct($options = null) {
    parent::__construct($options);
    $this->load_data();
  }

  /*
   * Ccылки можно показывать по частям
   */
  public function return_links($n = null, $offset = 0) {

    if (is_array($this->_links_page)) {

      $total_page_links = count($this->_links_page);

      if (!is_numeric($n) || $n > $total_page_links) {
        $n = $total_page_links;
      }

      $links = array();

      for ($i = 1; $i <= $n; $i++) {
        if ($offset > 0 && $i <= $offset) {
          array_shift($this->_links_page);
        } else {
          $links[] = array_shift($this->_links_page);
        }
      }

      $html = join($this->_links_delimiter, $links);
      
      // если запрошена определенная кодировка, и известна кодировка кеша, и они разные, конвертируем в заданную
      if (
        strlen($this->_charset) > 0
        &&
        strlen($this->_sape_charset) > 0
        &&
        $this->_sape_charset != $this->_charset
        &&
        function_exists('iconv')
      ) {
        $new_html  = @iconv($this->_sape_charset, $this->_charset, $html);
        if ($new_html) {
          $html = $new_html;
        }
      }
      
      if ($this->_is_our_bot) {
        $html = '<sape_noindex>' . $html . '</sape_noindex>';
      }
      
      return $html;

    } else {
      return $this->_links_page;
    }

  }

  public function _get_db_file() {
    if ($this->_multi_site) {
      return __DIR__ . '/' . $this->_host . '.links.db';
    } else {
      return __DIR__ . '/links.db';
    }
  }

  public function _get_dispenser_path() {
    return '/code.php?user=' . _SAPE_USER . '&host=' . $this->_host;
  }

  public function set_data($data) {
    $this->_links = $data;
    if (isset($this->_links['__sape_delimiter__'])) {
      $this->_links_delimiter = $this->_links['__sape_delimiter__'];
    }
    // определяем кодировку кеша
    if (isset($this->_links['__sape_charset__'])) {
      $this->_sape_charset = $this->_links['__sape_charset__'];
    } else {
      $this->_sape_charset = '';
    }
    if (@array_key_exists($this->_request_uri, $this->_links) && is_array($this->_links[$this->_request_uri])) {
      $this->_links_page = $this->_links[$this->_request_uri];
    } else {
      if (isset($this->_links['__sape_new_url__']) && strlen($this->_links['__sape_new_url__'])) {
        if ($this->_is_our_bot || $this->_force_show_code){
          $this->_links_page = $this->_links['__sape_new_url__'];
        }
      }
    }
  }
}
