<?php

abstract class RatingVoterAuth extends RatingVoterAbstract {

  /**
   * ID голосующего пользователя
   *
   * @var integer
   */
  protected $voterId;
  
  public function __construct(VoteObject $voteObject, $voterId) {
    parent::__construct($voteObject);
    $this->voterId = $voterId;
    $this->init();
  }
  
  protected function init() {
  }
  
}
