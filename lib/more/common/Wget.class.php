<?php

class Wget extends Options2 {
  
  public $options = array(
    'wget' => 'wget'
  );
  
  protected $requiredOptions = array(
    'tempFolder'
  );
  
  public function download($from, $to) {
    File::delete("{$this->options['tempFolder']}/wget.err");
    sys(
      "{$this->options['wget']} --timeout=600 --recursive --output-document=$to $from > ".
        "{$this->options['tempFolder']}/wget.out 2> {$this->options['tempFolder']}/wget.err",
      true
    );
    return $this->_exists(file_get_contents($this->options['tempFolder'].'/wget.err'));
  }
  
  public function touch($url) {
    sys("{$this->options['wget']} --spider $url");
  }
  
  public function exists($url) {
    sys(
      "{$this->options['wget']} --spider --timeout=5 --tries=1 --output-file={$this->options['tempFolder']}/wget.err $url",
      true
    );
    return $this->_exists(file_get_contents($this->options['tempFolder'].'/wget.err'));
  }
  
  protected function _exists($c) {
    LogWriter::str('ssss', $c);
    if (strstr($c, 'Bad Gateway')) return false;
    if (strstr($c, 'Connection timed out')) return false;
    if (strstr($c, 'ERROR 404: Not Found')) return false;
    if (strstr($c, 'Host not found')) return false;
    if (strstr($c, 'No such file or directory')) return false;
    return true;
  }

}
