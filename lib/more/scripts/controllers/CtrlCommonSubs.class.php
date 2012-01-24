<?php

class CtrlCommonSubs extends CtrlCommon {
  
  public function action_default() {
    if (!isset($this->oReq->r['code']))
      throw new NgnException("\$this->oReq->r['code'] not defined");
    if (!isset($this->oReq->r['subsId']))
      throw new NgnException("\$this->oReq->r['subsId'] not defined");
    if (!isset($this->oReq->r['type']))
      throw new NgnException("\$this->oReq->r['type'] not defined");
    new SubsReturn($this->oReq->r['subsId'], $this->oReq->r['code'], $this->oReq->r['type']);
    $this->redirect($this->oReq->r['link']);
  }

  public function action_unsubscribe() {
    if (!isset($this->oReq->r['listId']))
      throw new NgnException("\$this->oReq->r['listId'] not defined");
    if (!isset($this->oReq->r['code']))
      throw new NgnException("\$this->oReq->r['code'] not defined");
    if (!isset($this->oReq->r['type']))
      throw new NgnException("\$this->oReq->r['type'] not defined");
    if ($this->oReq->r['type'] == 'emails') {
      $r = db()->selectRow('SELECT * FROM subs_emails WHERE listId=?d AND code=?',
        $this->oReq->r['listId'], $this->oReq->r['code']);
      if (!$r) return;
      db()->query('DELETE FROM subs_emails WHERE listId=?d AND code=?',
        $this->oReq->r['listId'], $this->oReq->r['code']);
      LogWriter::str('unsubscribeEmails', $r['email']);
    } elseif ($this->oReq->r['type'] == 'users') {
      $r = db()->selectCell('
      SELECT subs_users.userId FROM subs_users, users
      WHERE
        subs_users.userId=users.id AND
        subs_users.listId=?d AND
        users.actCode=?',
      $this->oReq->r['listId'], $this->oReq->r['code']);
      if (!$r) return;
      db()->query('DELETE FROM subs_users WHERE listId=?d AND userId=?',
        $this->oReq->r['listId'], $r);
      LogWriter::str('unsubscribeUsers', $r);
    } else {
      throw new NgnException('Type "'.$this->oReq->r['type'].'" does not exists');
    }
    $this->hasOutput = false;
    $subsListTitle = db()->selectCell(
      'SELECT title FROM subs_list WHERE id=?d', $this->oReq->r['listId']);
    print 'Вы успешно отписаны от рассылки «'.$subsListTitle.'»';
  }

}