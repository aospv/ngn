<?php

class CronJobRatingGrade extends CronJobAbstract {
  
  public $period = 'daily';
  
  protected $now;
  
  protected $gradeSetPeriod;
  
  protected $gradeSetLastTime;
  
  public function __construct() {
    if (!($this->enabled = Config::getVarVar('rating', 'gradeEnabled', true)))
      return;
    $this->now = time();
    if (date('N') != Config::getVarVar('rating', 'gradeSetDay')) {
      $this->enabled = false;
      return;
    }
    $this->gradeSetPeriod = Config::getVarVar('rating', 'gradeSetPeriod')*60;
    $this->gradeSetLastTime = (int)Settings::get('ratingGradeSetTime');
    if ($this->gradeSetLastTime + $this->gradeSetPeriod > $this->now)
      $this->enabled = false;         
  }
  
  public function _run() {
    foreach (db()->ddTables() as $table) {
      $fields = db()->fields($table);
      if (!in_array('rating', $fields)) continue;
      
      $items = db()->select('
      SELECT id, rating FROM '.$table.'
      WHERE datePublish > ? AND datePublish < ? ORDER BY rating DESC',
      date('Y-m-d H:i:s', $this->gradeSetLastTime),
      date('Y-m-d H:i:s', $this->now)
      );
      
      $cnt = count($items);
      $gradeCount[5] = round($cnt * (Config::getVarVar('rating', 'grade5percent') / 100));
      $gradeCount[4] = round($cnt * (Config::getVarVar('rating', 'grade4percent') / 100));
      $gradeCount[3] = round($cnt * (Config::getVarVar('rating', 'grade3percent') / 100));
      
      $n = 0;
      foreach ($gradeCount as $grade => $cnt) {
        $ids[$grade] = array();
        for ($i=$n; $i<$n+$cnt; $i++) {
          $ids[$grade][] = $items[$i]['id'];
        }
        $n++;
      }
      foreach ($ids as $grade => $_ids) {
        db()->query('UPDATE '.$table.' SET rating_grade=?d WHERE id IN (?a)', $grade, $_ids);
      }
      Settings::set('ratingGradeSetTime', $this->now);
      print "Поставлено оценок ($table): ".Tt::enum($ids, ', ', '`<b>`.$k.`</b>: `.count($v)').'<br />';
    }
  }
  
}
