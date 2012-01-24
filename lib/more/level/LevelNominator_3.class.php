<?php

class LevelNominator_3 extends LevelNominator {
  
  /**
   * Сутки
   *
   * @var integer
   */
  protected $interval = 864200;
  
  protected $level = 3;

  protected $condition = 'or';
  
  protected $requirements = array(
    'dd' => 20,
    'comments' => 100,
  );
  
}
