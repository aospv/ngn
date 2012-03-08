<?php

abstract class SubPaMsgs extends SubPa {
  
  public $id1;
  
  public $id2;
  
  /**
   * @var Msgs
   */
  public $oMsgs;
  
  public $redirect;
  
  public $editTime;
  
  public $msgId;
  
  public $msgData;
  
  public $anonym = false;
  
  /**
   * @var FormSpamBotBlocker
   */
  public $oFSBB;
  
  public function __construct(CtrlCommon $oPA, $id1, $id2) {
    parent::__construct($oPA);
    Misc::checkEmpty($id1);
    Misc::checkEmpty($id2);
    $this->id1 = $id1;
    $this->id2 = $id2;
  }
  
  public function init() {
    $this->editTime = 30*30*0.5;
    $this->msgId = !empty($this->oPA->oReq->r['id']) ? $this->oPA->oReq->r['id'] : null;
    $this->d['tpl'] = 'common/msgs';
    $this->setAnonym();
    $this->initMsgs();
    $this->setLevel();
    //$this->d['priv'] = $this->oPA->priv;
    $this->d['ctrl'] = $this->oPA;
  }
  
  protected function setAnonym() {
    if (!empty($this->oPA->settings['allowAnonym']) and !Auth::get('id')) {
      $this->d['anonym'] = $this->anonym = true;
    }
  }
  
  protected function setLevel() {
    $this->d['level'] = 
      db()->selectCell('SELECT level FROM level_users WHERE userId=?d', Auth::get('id'));
  }

  protected function getMsgData() {
    if (!$this->msgId) return false;
    $this->msgData = $this->oMsgs->getMsg($this->msgId);
    return $this->msgData;
  }
    
  abstract protected function initMsgs();
  
  public function action_default() {
    $this->d['items'] = $this->oMsgs->getMsgsPaged();
    $this->d['pagination']['pNums'] = $this->oMsgs->pNums;
    $this->setSpamBotBlocker(); // Инициализируем анти-спам систему
    $this->d['fsbbTags'] = $this->oFSBB->makeTags(); // Получаем скрытые анти-спам поля
    $this->d['subscribed'] = $this->oMsgs->isSubscribed(Auth::get('id'));
  }
  
  public function action_ajax_getText() {
    if (!$msgData = $this->getMsgData()) return;
    $this->oPA->ajaxOutput = $msgData['text'];
  }  

  public function action_ajax_update() {
    if (!$msgId = (int)$this->oPA->oReq->r['id'] or !$msgData = $this->oMsgs->getMsg($msgId)) return;
    $this->oMsgs->update($this->oPA->oReq->r['id'], $this->oPA->oReq->r['text']);
    $msgData = $this->oMsgs->getMsg($msgId);
    $this->oPA->ajaxOutput = $msgData['text_f'];
  }
  
  public function action_ajax_activate() {
    $this->oMsgs->activate($this->oPA->oReq->r['id']);
    $this->oPA->ajaxSuccess = true;
  }
  
  public function action_ajax_deactivate() {
    $this->oMsgs->deactivate($this->oPA->oReq->r['id']);
    $this->oPA->ajaxSuccess = true;
  }
  
  public function action_ajax_delete() {
    $this->oMsgs->delete($this->oPA->oReq->r['id']);
    $this->oPA->ajaxSuccess = true;
  }
  
  public function action_json_refrash() {
  }
  
  public function action_json_create() {
    if (($id = $this->create()) === false) {
      $this->oPA->json['error'] = $this->error;
      return;
    }
    $msg = $this->oMsgs->getMsgF($id);
    $this->oPA->json['msgsIds'][] = $id;
    $this->oPA->json['msgsHtml'][] = Tt::getTpl('common/msg', $msg);
  }
  
  public function action_create() {
    if ($this->anonym and !$this->oPA->oReq->r['nick']) {
      $this->d['errors'][] = 'Введите ник';
      $this->oPA->action_default();
      return;
    }
    $this->create();
    $this->redirect();
  }
  
  protected $error;
  
  protected function create() {
    if (trim($this->oPA->oReq->r['text']) == '') {
      $this->error = 'Текст пустой';
      return false;
    }
    try {
      $d = $this->oPA->oReq->r;
      $d['userId'] = Auth::get('id');
      $d['userGroupId'] = $this->oPA->userGroup ? $this->oPA->userGroup['id'] : 0;
      $this->oMsgs->create($d);
    } catch (NgnValidError $e) {
      $this->error = $e->getMessage();
      return false;
    }
    if (method_exists($this->oPA, 'updateCommentsDate'))
      $this->oPA->updateCommentsDate();
    return $id;
  }
  
  public function action_subscribe() {
    $this->oMsgs->subscribe(Auth::get('id'));
    $this->redirect();
  }
  
  public function action_unsubscribe() {
    $this->oMsgs->unsubscribe(Auth::get('id'));
    $this->redirect();
  }
  
  /**
   * Inline Images Upload
   */
  public function action_iiUpload() {
    $this->d['mainTpl'] = 'popups/uploadImage';
    $this->d['file'] = $this->oMsgs->iiUpload($_FILES['image']);
  }
  
  protected function setSpamBotBlockerAction() {
    $this->setSpamBotBlocker();
    if ($_POST) $param = $_POST;
    elseif ($_GET) $param = $_GET;
    if (!$param) Err::warning("This script requires some POST or GET parameters");
    $this->d['nospam'] = $this->oFSBB->checkTags($param);
    return $this->d['nospam'] ? true : false;
  }
  
  protected function setSpamBotBlocker() {
    $this->oFSBB = new FormSpamBotBlocker();
    $this->oFSBB->setTimeWindow(2, 30); // set the min and max time in seconds for a form to be submitted
    $this->oFSBB->setTrap(true, 'mail', 'checkthesun'); // called here, because it has been called on the form page as well (same trap name!)
    $this->oFSBB->hasSession = false;
    // $submissions = $_SESSION[$blocker->sesName];  
  }
  
}
