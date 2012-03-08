<?php

require_once 'System/Daemon.php';

abstract class NgnClDaemon extends Options2 {
  
  protected $argv;
  
  public $options = array(
    'iterTime' => 5,
    'no-daemon' => false,
    'help' => false,
    'write-initd' => false,
  );
  
  public function __construct(array $argv, array $options = array()) {
    parent::__construct($options);
    $this->argv = $argv;
    NgnCl::parseArgv($this->argv, $this->options);
    if ($this->options['write-initd'])
      $this->options['no-daemon'] = true;
    $this->help();
    System_Daemon::setOptions(array(
      'appName' => 'ngnd',
      'appDir' => __DIR__,
      'appDescription' => 'Sample ngn daemon',
      'authorName' => 'masted',
      'authorEmail' => 'masted311@gmail.com',
      'sysMaxExecutionTime' => '0',
      'sysMaxInputTime' => '0',
      'sysMemoryLimit' => '64M',
      'appRunAsGID' => 1000,
      'appRunAsUID' => 1000,
    ));
    if (!$this->options['no-daemon']) System_Daemon::start();
    $this->writeInitD();
    $this->init();
    $this->start();
    System_Daemon::stop();
  }
  
  protected function init() {}
  
  protected function help() {
    if ($this->options['help']) {
      echo 'Usage: '.$this->argv[0].' [runmode]'."\n";
      echo 'Available runmodes:'."\n";
      foreach (array_keys($this->options) as $runmod) {
        echo ' --'.$runmod . "\n";
      }
    }
  }
  
  protected function writeInitD() {
    if (!$this->options['write-initd']) {
      System_Daemon::info('not writing an init.d script this time');
    } else {
      if (($initdLocation = System_Daemon::writeAutoRun()) === false) {
        System_Daemon::notice('unable to write init.d script');
      } else {
        System_Daemon::info(
          'sucessfully written startup script: %s',
          $initdLocation
        );
      }
    }
  }
  
  /**
   * Номер итерации
   * @var integer
   */
  protected $n;
  
  protected $startTime;
  
  protected function start() {
    $runningOkay = true;
    $this->n = 1;
    $this->startTime = time();
    while (!System_Daemon::isDying() and $runningOkay) {
      $mode = '"'.(System_Daemon::isInBackground() ? '' : 'non-' ).'daemon" mode';
      System_Daemon::info('{appName} running in %s %s/*', $mode, $this->n);
      // $runningOkay = parseLog('vsftpd');
      //if (!$runningOkay) {
      //  System_Daemon::err('parseLog() produced an error, '.
      //    'so this will be my last run');
      //  }
      //}
      $this->iteration();
      System_Daemon::iterate(2);
      if ($this->options['iterTime']) sleep($this->options['iterTime']);
      $this->n++;
    }
  }
  
  abstract protected function iteration();
  
}
