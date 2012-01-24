<?php

class CtrlAdminPrivMsgs extends CtrlAdmin {

  static $properties = array(
    'title' => 'Сообщения',
    'onMenu' => true,
    'order' => 20
  );
  
  function init() {
    if (!$this->oPM) throw new NgnException('$this->oPM not defined');
    $this->d['tpl'] = 'privMsgs/default';
  }

  function action_default() {
    $this->d['msgs'] = $this->oPM->getAllMsgs();
  }
  
  function action_delete() {
    $this->oPM->deleteMsgs($this->userId, array($this->oReq->r['id']));
    $this->redirect();
  }
  
  function action_clear() {
    $this->oPM->clearMsgs($this->userId);
    $this->redirect();
  }
  
  function action_send() {
    $this->oPM->sendMsg(Auth::get('id'), $this->oReq->r['user'], $this->oReq->r['text']);
    $this->redirect(Tt::getPath(2).'/sendComplete');
  }
  
  function action_sendComplete() {
    $this->d['tpl'] = 'privMsgs/complete';
  }
  
  function action_sendPage() {
    $this->d['toUser'] = DbModelCore::get('users', $this->oReq->r['userId']);
    $this->d['tpl'] = 'privMsgs/send';
  }

}