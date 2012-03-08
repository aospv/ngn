<?php

class CtrlAdminSubscribe extends CtrlAdmin {

  static $properties = array(
    'title' => 'Рассылка',
    'onMenu' => true
  );
  
  public function init() {
    if (!isset($this->params[3])) return;
    $listId = $this->getParam(3);
    $this->d['emailsCnt'] = db()->selectCell(
      'SELECT COUNT(*) FROM subs_emails WHERE listId=?d', $listId);
    $this->d['usersCnt'] = db()->selectCell(
      'SELECT COUNT(*) FROM subs_users WHERE listId=?d', $listId);
    $this->d['list'] = db()->selectRow('SELECT * FROM subs_list WHERE id=?d',
      $listId);
  }

  public function action_default() {
    $this->d['items'] = db()->query('SELECT * FROM subs_list ORDER BY subs_list.id');
    $this->d['tpl'] = 'subscribe/default';
  }
  
  public function action_delete() {
    $listId = $this->oReq->r['id'];
    db()->query('DELETE FROM subs_list WHERE id=?d', $listId);
    db()->query('DELETE FROM subs_emails WHERE listId=?d', $listId);
    db()->query('DELETE FROM subs_users WHERE listId=?d', $listId);
    foreach (db()->selectCol(
    'SELECT id FROM subs_subscribes WHERE listId=?d', $this->oReq->r['id']) as $subsId) {
      $this->deleteSubscribe($subsId);
    }
    $this->redirect();
  }
  
  public function action_activate() {
    db()->query('UPDATE subs_list SET active=1 WHERE id=?d', $this->oReq->r['id']);
    $this->redirect();
  }
  
  public function action_deactivate() {
    db()->query('UPDATE subs_list SET active=0 WHERE id=?d', $this->oReq->r['id']);
    $this->redirect();
  }
  
  public function action_deleteEmail() {
    $listId = $this->getNumParam(3);
    db()->query('DELETE FROM subs_emails WHERE listId=?d AND email=?',
      $listId, $this->oReq->r['email']);
    $this->redirect(Tt::getPath(4));
  }
  
  public function action_deleteUser() {
    $listId = $this->getNumParam(3);
    db()->query('DELETE FROM subs_users WHERE listId=?d AND userId=?',
      $listId, $this->oReq->r['userId']);
    $this->redirect();
  }
  
  public function action_deleteSubs() {
    $this->deleteSubscribe($this->oReq->r['id']);
    $this->redirect();
  }
  
  public function action_closeSubs() {
    db()->query('UPDATE subs_subscribes SET subsEndDate=? WHERE id=?d',
      dbCurTime(), $this->oReq->r['id']);
    $this->redirect();
  }
  
  protected function deleteSubscribe($subsId) {
    db()->query('DELETE FROM subs_subscribes WHERE id=?d', $subsId);
    db()->query('DELETE FROM subs_subscribers WHERE subsId=?d', $subsId);
    db()->query('DELETE FROM subs_returns WHERE subsId=?d', $subsId);
  } 
  
  /**
   * @var Form
   */
  protected $oForm;
  
  protected function processListForm($btnTitle = 'Создать') {
    $this->oF = new Form(new Fields(array(
      array(
        'title' => 'Название',
        'name' => 'title',
        'required' => true
      ),
      array(
        'title' => "Использовать в рассылке email'ы зарегистрированых пользователей",
        'name' => 'useUsers',
        'type' => 'bool'
      ),
      array(
        'title' => "Текст",
        'name' => 'text',
        'type' => 'wisiwig'
      ),
    )), array('submitTitle' => $btnTitle));
  }
  
  /**
   * Создание нового листа
   */
  public function action_new() {
    $this->processListForm();
    if ($this->oF->isSubmittedAndValid()) {
      $id = db()->query('INSERT INTO subs_list SET title=?, useUsers=?, text=?',
        $_POST['title'], $_POST['useUsers'], $_POST['text']);
      $this->redirect(Tt::getPath(2).'/emails/'.$id);
      return;
    }
    $this->d['form'] = $this->oF->html();
    $this->d['tpl'] = 'subscribe/new';
    $this->setPageTitle('Создание рассылки');
  }
  
  /**
   * Редактирование листа
   */
  public function action_edit() {
    $listId = $this->getNumParam(3);
    $this->processListForm('Сохранить');
    $this->oF->setElementsData($_POST ? $_POST :
      db()->selectRow('SELECT * FROM subs_list WHERE id=?d', $listId));
    if ($this->oF->isSubmittedAndValid()) {
      db()->query('UPDATE subs_list SET title=?, useUsers=?, text=? WHERE id=?d',
        $_POST['title'], $_POST['useUsers'],
        O::get('FormatText')->html($_POST['text']),
        $listId);
      $this->redirect();
      return;
    }
    $this->d['form'] = $this->oF->html();
    $this->d['tpl'] = 'subscribe/new';
    $this->setPageTitle('Редактирование рассылки');
  }
  
  public function action_newEmail() {
    $listId = $this->getNumParam(3);
    $oF = new Form(new Fields(array(
      array(
        'title' => 'E-mail',
        'name' => 'email',
        'type' => 'email',
        'required' => true
      )
    )), array('submitTitle' => 'Добавить'));
    if ($oF->isSubmittedAndValid()) {
      db()->query('INSERT INTO subs_emails SET listId=?d, email=?, code=?',
        $listId, $_POST['email'], Misc::randString());
      $this->redirect(Tt::getPath(2).'/emails/'.$listId);
      return;
    }
    $this->setPageTitle("Добавление e-mail'а");
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'subscribe/new';
  }
  
  public function action_emails() {
    $listId = $this->getNumParam(3);
    Pagination::$n = 50;
    Pagination::$whereCond = "listId=".mysql_escape_string($listId);
    list($this->d['pagination']['pNums'], $limit) = Pagination::get('subs_emails');
    $this->d['emails'] = db()->query(
      "SELECT * FROM subs_emails WHERE listId=?d LIMIT $limit", $listId);
    $this->setPageTitle("E-mail'ы");
    $this->d['tpl'] = 'subscribe/emails';
  }
  
  public function action_users() {
    $listId = $this->getNumParam(3);
    
    Pagination::$n = 50;
    list($this->d['pagination']['pNums'], $limit) = Pagination::get2(db()->selectCell("
    SELECT COUNT(*) FROM subs_users, users
    WHERE
      subs_users.userId=users.id AND
      subs_users.listId=?d AND
      users.active=1
    ", $listId));
    
    $this->d['tpl'] = 'subscribe/users';
    $this->d['users'] = db()->query("
    SELECT
      users.id,
      users.login,
      users.email,
      users.actCode AS code
    FROM subs_users, users
    WHERE
      subs_users.userId=users.id AND
      subs_users.listId=?d AND
      users.active=1
    LIMIT $limit
    ", $listId);
  }
  
  public function action_import() {
    $listId = $this->getNumParam(3);
    $oF = new Form(new Fields(array(
      array(
        'title' => "E-mail'ы через запятую",
        'name' => 'emails',
        'type' => 'textarea'
      ),
    )), array('submitTitle' => 'Импортировать'));
    if ($oF->isSubmittedAndValid()) {
      $this->import($listId, explode(',', $_POST['emails']));
      $this->redirect(Tt::getPath(2).'/emails/'.$listId);
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'subscribe/new';
    $this->setPageTitle("Импортировать e-mail'ы");
  }
  
  public function action_send() {
    $listId = $this->getNumParam(3);
    $this->setPageTitle('Создание и отправка новой рассылки листа «'.
      db()->selectCell('SELECT title FROM subs_list WHERE id=?d', $listId).
      '»'
    );
    $this->d['unfinishedSubs'] = db()->query("
    SELECT
      *,
      UNIX_TIMESTAMP(subsBeginDate) AS subsBeginDate_tStamp
    FROM subs_subscribes WHERE listId=?d AND subsEndDate='0000-00-00'", $listId);
    $this->d['tpl'] = 'subscribe/send';
  }

  /*
  public function action_makeSend() {
    set_time_limit(300);
    $listId = $this->getNumParam(3);
    $o = new SubsSender($listId);
    $o->send();
    $this->redirect(Tt::getPath(2).'/emails/'.$listId);
  }
  */
  
  protected function import2($listId, $emails) {
    $emails = array_map('trim', $emails);
    foreach($emails as $email)
      db()->query('REPLACE INTO subs_emails SET listId=?d, email=?, code=?',
      $listId, $email, Misc::randString());
  }
  
  protected function import($listId, $emails) {
    foreach(array_map('trim', $emails) as $email) {
      $data[] = array(
        'listId' => $listId,
        'email' => $email,
        'code' => Misc::randString()
      );
    }
    db()->multiInsertAddIdColumn('subs_emails', $data);    
  }
  
  public function action_returns() {
    $subsId = $this->getNumParam(4);
    Pagination::$n = 50;
    Pagination::$whereCond = "subsId=".mysql_escape_string($subsId);
    list($this->d['pagination']['pNums'], $limit) = Pagination::get('subs_returns');
    $this->d['items'] = db()->query("
      SELECT *, UNIX_TIMESTAMP(returnDate) AS returnDate_tStamp
      FROM subs_returns WHERE subsId=?d
      ORDER BY returnDate DESC
      LIMIT $limit", $subsId);
    $this->d['tpl'] = 'subscribe/returns';
  }
  
  public function action_subs() {
    $this->setPageTitle("Прошедшие рассылки");
    $this->d['tpl'] = 'subscribe/subs';
    $listId = $this->getNumParam(3);
    $this->d['items'] = db()->query('
      SELECT
        id AS ARRAY_KEY,
        id,
        listId,
        text,
        UNIX_TIMESTAMP(subsBeginDate) AS subsBeginDate_tStamp,
        UNIX_TIMESTAMP(subsEndDate) AS subsEndDate_tStamp
      FROM subs_subscribes
      WHERE listId=?d
      ORDER BY subsBeginDate DESC
    ', $listId);
    if (!$this->d['items']) return;
    $subsIds = array_keys($this->d['items']);
    $returnsCnts = db()->selectCol('
      SELECT subsId AS ARRAY_KEY, COUNT(*) AS cnt FROM subs_returns
      WHERE subsId IN (?a) GROUP BY subsId',
      $subsIds);
    $sentCnts = db()->selectCol("
      SELECT subsId AS ARRAY_KEY, COUNT(*) AS cnt FROM subs_subscribers
      WHERE subsId IN (?a) AND status='complete' GROUP BY subsId",
      $subsIds);

    $totalCnts = db()->selectCol("
      SELECT subsId AS ARRAY_KEY, COUNT(*) AS cnt FROM subs_subscribers
      WHERE subsId IN (?a) GROUP BY subsId",
      $subsIds);
    foreach (array_keys($this->d['items']) as $subsId) {
      $this->d['items'][$subsId]['returnsCnt'] = 
        isset($returnsCnts[$subsId]) ? $returnsCnts[$subsId] : 0;
      $this->d['items'][$subsId]['sentCnt'] = 
        isset($sentCnts[$subsId]) ? $sentCnts[$subsId] : 0;
      $this->d['items'][$subsId]['totalCnt'] = 
        isset($totalCnts[$subsId]) ? $totalCnts[$subsId] : 0;
    }
  }
  
  public function action_json_send() {
    $oSS = new SubsSender(
      $this->getNumParam(3),
      isset($this->oReq->r['subsId']) ? $this->oReq->r['subsId'] : null
    );
    
    // Подчищаем неотправленные
    if ($this->oReq->r['step'] == 0)
      db()->query("UPDATE subs_subscribers SET status='sent' WHERE status='process'");
    
    $oSSPJ = new SubsSenderPartialJob($oSS);
    $oSSPJ->jobsInStep = Config::getVarVar('subscribe', 'jobsInStep');
    $this->json = $oSSPJ->makeStep($this->oReq->r['step']);
    $this->json['subsId'] = $oSS->getSubsId();
  }
  
}