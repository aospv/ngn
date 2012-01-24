<?php

class SubsSenderPartialJob {
  
  /**
   * @var SubsSender
   */
  public $oSender;
  
  protected $jobsTotal;
  
  public $jobsInStep = 10;
  
  public function __construct(SubsSender $oSender) {
    $this->oSender = $oSender;
    $this->jobsTotal = db()->selectCell('SELECT COUNT(*) FROM subs_subscribers WHERE subsId=?d',
      $this->oSender->getSubsId());
  }
  
  protected function getJobsCompleteN() {
    return db()->selectCell(
      "SELECT COUNT(*) FROM subs_subscribers WHERE subsId=?d AND status='complete'",
      $this->oSender->getSubsId());
  }
  
  public function makeStep($step) {
    // Инициализация массива $this->jobs будет проходить тут, потому что на
    // только этоп этапе нам известны данные для получения заданий, т.к.
    // нельзя получать массив всех заданий (он слишком велик)
    $fromSubscriberN = $this->jobsInStep * $step;
    foreach (db()->query("
    SELECT * FROM subs_subscribers WHERE subsId=?d AND status=''
    ORDER BY n
    LIMIT $fromSubscriberN, {$this->jobsInStep}",
    $this->oSender->getSubsId()) as $v) {
      $this->sendEmail($v);
    }
    $jobsRemains = $this->jobsTotal - $this->getJobsCompleteN();
    if ($jobsRemains == 0) $this->oSender->endSubscribe();
    $stepsTotal = ceil($this->jobsTotal / $this->jobsInStep);
    $stepsRemains = ceil($jobsRemains / $this->jobsInStep);
    return array(
      'jobsTotal' => $this->jobsTotal,
      'stepsTotal' => $stepsTotal,
      'jobsRemains' => $jobsRemains,
      'stepsRemains' => $stepsRemains
    );
  }
  
  /**
   * Send email
   *
   * @param array Subscriber record
   */
  protected function sendEmail($v) {
    db()->query('UPDATE subs_subscribers SET status=? WHERE n=?d AND subsId=?d',
      'process', $v['n'], $this->oSender->getSubsId());
    // ---------------------- 
    try {
      $this->oSender->sendEmail($v);
    } catch (NgnException $e) {}
    // ---------------------- 
    db()->query('UPDATE subs_subscribers SET status=? WHERE n=?d AND subsId=?d',
      'complete', $v['n'], $this->oSender->getSubsId());
  }
  
}
