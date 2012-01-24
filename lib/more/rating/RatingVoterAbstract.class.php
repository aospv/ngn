<?php

/**
 * Субъект голосования
 * Знает можен он голосовать или нет, сколько голосов может отдавать и 
 * за кого будет голосовать
 */
abstract class RatingVoterAbstract {
  
  /**
   * @var VoteObject
   */
  protected $voteObject;
  
  /**
   * Определяет может ли субъект отдавать отрицательное количество голосов
   *
   * @var bool
   */
  protected $isMinus;
  
  /**
   * Максимальное количество голосов, которое может отдать субъект
   *
   * @var integer
   */
  protected $maxStarsN;
  
  public function __construct(VoteObject $voteObject) {
    $this->voteObject = $voteObject;
    $this->maxStarsN = Config::getVarVar('rating', 'maxStarsN');
    $this->isMinus = Config::getVarVar('rating', 'isMinus');
  }
  
  public function vote($id, $n) {
    if ($this->voted($id))
      throw new NgnException('You already voted');
    if (!$this->checkMaxN($n)) return false;
    $this->voteObject->vote($id, $n);
    $this->logVoter($id, $n);
    $this->saveAverage($id, $n);
    return true;
  }
  
  protected function checkMaxN($n) {
    if ($this->isMinus)
      $n = $n < 0 ? -$n : $n;
    elseif ($n < 0)
      throw new NgnException("Minus stars not allowed");
    if ($n > $this->maxStarsN)
      throw new NgnException("Max stars number is {$this->maxStarsN}");
    return true;
  }
  
  protected function getValue($n) {
    // if ($this->isMinus) $n*2
  }
    
  abstract protected function voted($id);
  
  abstract protected function saveAverage($id);
  
  abstract protected function logVoter($id, $n);
  
  abstract protected function getVotedIds(array $inIds);
  
}
