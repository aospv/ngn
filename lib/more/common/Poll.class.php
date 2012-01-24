<?php

define("POLL_ONE_ANSWER", 0);
define("POLL_SEVERAL_ANSWERS", 1);

class Poll {

  var $pollId;
  var $voteId;
  var $action;
  var $isVoted = false;
  var $pollData;
  var $polls;

  var $tplData = array();

  /**
   * Шаблон для вывода результатов
   *
   * @var string
   */
  var $tplResults = 'poll.results';

  /**
   * Шаблон для вывода вариантов ответов
   *
   * @var string
   */
  var $tplVariants = 'poll.variants';
  
  /**
   * Конструктор
   *
   * @param   integer ID опроса
   * @param   bool    Если true, обработка опроса происходит извне
   * @return Poll
   */
  function Poll() {
    $this->postVoteId = (int)$_REQUEST["voteId"];
    $this->postPollId = (int)$_REQUEST["pollId"];
    $this->postVoteIds = $_REQUEST["voteIds"];
    $this->action = $_REQUEST["action"];
  }
  
  function processPoll($pollId = null) {
    if (!$pollId) {
      // Если ID отпроса не определён, берём первый активный из списка без нулевыми ID страниц и тем
      $this->pollData = $this->getFirstActivePoll();
    } else {
      $this->pollData = $this->getPoll($pollId);
    }
    if (!$this->pollData) return false;
    $this->pollId = $this->pollData["id"];
    $this->tplData = $this->pollData;
    // Голосовал или нет
    $this->isVoted = $this->isVoted();    
    // Сохраняем
    if ($this->action == "pollPosting") {
      if ($this->pollId == $this->postPollId) {
        // print "{$this->pollId} == {$this->postPollId}";
        // Если ID текущего опроса и ID поста опроса совпадают
        $this->saveResults();
      }
    }
    return true;
  }
  
  function printPollRandom() {
    $query = "SELECT id FROM poll1 ORDER BY rand() LIMIT 1";
    $r = mysql_query($query);
    list($pollId) = mysql_fetch_row($r);
    $this->printPoll($pollId);
  }

  function getPolls() {
    $query = "SELECT * FROM poll1";
    $r = mysql_query($query);
    while ($row = mysql_fetch_assoc($r)) {
      $rows[$row["id"]] = $row;
    }
    return $rows;
  }

  function getPollsTitles() {
    $pollTitles = array();
    if (!$this->polls) {
      if (!$this->polls = $this->getPolls()) return false;
    }
    foreach ($this->polls as $k => $v) {
      $pollTitles[$k] = $v["title"];
    }
    return $pollTitles;
  }
  
  /**
   * Определяет голосовали ли за текущий опрос
   *
   * @return bool
   */
  function isVoted() {
    $query = "SELECT * FROM poll_ips 
              WHERE poll_id=".$this->pollId." AND ip='".$_SERVER["REMOTE_ADDR"]."'";
    $r = mysql_query($query);
    if (mysql_num_rows($r) == 0) {
      return false;
    } else {
      return true;
    }
  }

  function getVote($voteId) {
    $query = "SELECT * FROM poll2 WHERE id=$voteId";
    $r = mysql_query($query);
    if (mysql_num_rows($r) == 0) return false;
    $row = mysql_fetch_assoc($r);
    return $row;
  }

  function saveResults() {
    if (!$this->postVoteId and !$this->postVoteIds) {
      $this->tplData["alert"] = "Выберете вариант ответа";
    } else {
      if ($this->pollData["type"] == POLL_SEVERAL_ANSWERS and $this->voteIds and is_array($this->voteIds)) {
        foreach ($this->postVoteIds as $k => $voteId) {
          $res = $this->makeVote($voteId);
          if (!$res) break;
        }
      } else {
        $res = $this->makeVote($this->postVoteId);
      }
      if ($res) {
        $query = "INSERT INTO poll_ips (ip, poll_id) 
                  VALUES ('".$_SERVER["REMOTE_ADDR"]."', ".$this->pollId.")";
        query_sql($query);
      }
    }
    return $res;
  }

  function makeVote($voteId) {
    $voteData = $this->getVote($voteId);
    if (!$voteData) {
      $this->tplData["alert"] = "Нет такого варианта ответа";
      return false;
    }
    /**
     * @deprecated За любой опрос можно проголосовать, если он ещё не закрыт
     *             Формулировка "не за тот" неправильная
    if ($voteData["pollid"] != $this->pollId) {
      $this->tplData["alert"] = "Вы голосуете не за тот опрос";
      return false;
    } else {
     */
    if ($this->isVoted) {
      $this->tplData["alert"] = "Вы уже голосовали в этом опросе";
      return false;
    } else {
      return $this->iterateVote($voteId);
    }
  }
  
  function iterateVote($voteId) {
    if (!$voteId = (int)$voteId) return false;
    $query = "UPDATE poll2 SET votes=votes+1 WHERE id=".$voteId;
    query_sql($query);
    return true;
  }

  function getVotes($isResults = false) {
    if ($isResults) $Order = "votes DESC";
    else $Order = "oid";
    $query = "SELECT * FROM poll2 WHERE pollid=".$this->pollId." ORDER BY $Order";
    $r = query_sql($query);
    if (mysql_num_rows($r) > 0) {
      $votesSum = 0;
      while ($row = mysql_fetch_assoc($r)) {
        $rows[] = $row;
        $votesSum += $row["votes"];
      }
      $this->tplData['votesSum'] = $votesSum;
      foreach ($rows as $k => $v) {
        if ($votesSum == 0) $rows[$k]["procs"] = 0;
        else $rows[$k]["procs"] = round($v["votes"] / $votesSum * 100);
      }
      return $rows;
    }
    return false;
  }

  function printPoll($pollId = null) {
    if (!$this->processPoll($pollId)) return false;
    $isResults = false;
    if ($this->action == "pollResults" or $this->action == "pollPosting" or $this->isVoted) {
      $isResults = true;
    }
    if ($variants = $this->getVotes($isResults)) {
      $this->tplData["variants"] = $variants;
      if ($isResults) {
        printTpl($this->tplResults, $this->tplData);
      } else {
        printTpl($this->tplVariants, $this->tplData);
      }
    } else {
      return false;
    }
  }
  
  function getPrintPoll($pollId = null) {
    ob_start();
    $this->printPoll($pollId);
    $c = ob_get_contents();
    ob_clean();
    return $c;
  }

  function getFirstActivePoll() {
    $query = "SELECT * FROM poll1 WHERE showpoll=1 ORDER BY date1 LIMIT 1";
    $r = mysql_query($query);
    if (mysql_num_rows($r) == 0) return false;
    else $row = mysql_fetch_assoc($r);
    return $row;
  }

  function getPoll($pollId) {
    $query = "SELECT * FROM poll1 WHERE id=$pollId";
    $r = mysql_query($query);
    if (mysql_num_rows($r) == 0) return false;
    else $row = mysql_fetch_assoc($r);
    return $row;
  }

}
