<?php

class LevelNominator_2 extends LevelNominator {
  
  /**
   * Час
   *
   * @var integer
   */
  protected $interval = 432100;
  
  protected $level = 2;

  protected $condition = 'or';
  
  protected $requirements = array(
    'dd' => 7,
    'comments' => 30
  );
  
}
