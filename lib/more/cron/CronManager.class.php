<?php

class CronManager {
  
  protected $jobs;
  
  public function __construct() {
    $this->initJobs();
  }
  
  private function initJobs() {
    foreach (ClassCore::getDescendants('CronJobAbstract') as $v) {
      /* @var $oCronJobAbstract CronJobAbstract */
      $oCronJob = O::get($v['class']);
      if (!$oCronJob->enabled) {
        print "<br />Класс ".$v['class'].' выключен';
        continue;
      }
      if (!isset($oCronJob->period))
        throw new NgnException($v['class'].' period does not set');
      $this->jobs[$oCronJob->period][$v['name']] = $oCronJob;
    }
  }
  
  protected function getJobs($period) {
    if (!isset($this->jobs[$period])) return false;
    //throw new NgnException("Jobs in '$period' period does not exists");
    return $this->jobs[$period];
  }

  /**
   * @param   string    5minute/daily/hourly
   */
  public function run($period) {
    if (!($jobs = $this->getJobs($period))) return false;
    //$c = "<h2>'$period' report at ".date('d.m.Y H:i:s').'</h2>';
    $n = 0;
    foreach ($jobs as $name => $oJob) {
      /* @var $oJob CronJobAbstract */
      $c .= $oJob->run();
      $jobNames[] = $name;
      $n++;
    }
    $c .= "<br />Выполнено заданий: $n (".implode(', ', $jobNames).")<br />";
    $reportDir = UPLOAD_PATH.'/cron/';
    Dir::make($reportDir);
    /*
    if (file_exists($reportDir.'report.html')) {
      $cc = file_get_contents($reportDir.'report.html');
      $cc = $c . $cc;
    } else {
      $cc = $c;
    }
    //file_put_contents($reportDir.'report.html', $cc);
    */
    LogWriter::html('cron', $c, array('period' => $period));
    print $c;
    print 'done.';
  }
  
}
