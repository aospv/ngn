<?php

class Msgs {
  
  public $table;
  
  public $id1Field;
  
  public $id2Field;
  
  public $id1;
  
  public $id2;
  
  public $subscribeType;
  
  public $canEdit = false;
  
  /**
   * @var DbCond
   */
  public $cond;
  
  /**
   * HTML с сылками на страницы для постраничнго вывода
   *
   * @var string
   */
  public $pNums;
  
  /**
   * Количество записей на странице для постраничного вывода
   *
   * @var intger
   */
  public $n = 60;
  
  /**
   * Конструктор
   *
   * @param   string    Таблица с сообщениями
   * @param   string    Имя поля с первым ID
   * @param   string    Имя поля со вторым ID
   * @param   integer   ID1
   * @param   integer   ID2
   * @param   string    см. notify/Notify_SubscribeTypes
   */
  public function __construct($table, $id1Field, $id2Field, $id1, $id2, $subscribeType) {
    $this->table = $table;
    $this->id1Field = $id1Field;
    $this->id2Field = $id2Field;
    $this->id1 = $id1;
    $this->id2 = $id2;
    $this->subscribeType = $subscribeType;
    $this->cond = DbCond::get($table)->
      addF('active', 1)->
      addF($id1Field, $id1)->
      addF($id2Field, $id2);
  }

  public function getMsg($id) {
    return db()->selectRow("
    SELECT *,
      UNIX_TIMESTAMP(dateCreate) AS dateCreate_tStamp,
      UNIX_TIMESTAMP(dateUpdate) AS dateUpdate_tStamp
    FROM {$this->table} WHERE id=?d", $id);
  }
  
  public function getMsgF($id) {
    $r = db()->selectRow("
    SELECT
      t.*,
      t.text_f AS text,
      UNIX_TIMESTAMP(t.dateCreate) AS dateCreate_tStamp,
      UNIX_TIMESTAMP(t.dateUpdate) AS dateUpdate_tStamp,
      users.login,
      users2.login AS ansLogin
    FROM {$this->table} AS t
    LEFT JOIN users AS users ON t.userId=users.id
    LEFT JOIN users AS users2 ON t.ansUserId=users2.id
    WHERE t.id=?d", $id);
    $r += UsersCore::getImageData($r['userId']);
    return $r;
  }
  
  public function getMsgsPaged() {
    list($this->pNums, $limit) = O::get('Pagination')->get($this->table, $this->cond);
    return $this->getMsgs($limit);
  }
  
  public function getMsgs($limit, $order = 'dateCreate DESC') {
    $cond = ClassCore::clon($this->cond)->setLimit($limit)->setOrder($order)->all();
    $r = db()->query("
    SELECT
      {$this->table}.*,
      {$this->table}.text_f AS text,
      UNIX_TIMESTAMP({$this->table}.dateCreate) AS dateCreate_tStamp,
      UNIX_TIMESTAMP({$this->table}.dateUpdate) AS dateUpdate_tStamp,
      users.login,
      users2.login AS ansLogin
    FROM {$this->table}
    LEFT JOIN users AS users ON {$this->table}.userId=users.id
    LEFT JOIN users AS users2 ON {$this->table}.ansUserId=users2.id
    {$cond}"
    );
    foreach ($r as $k =>& $v) {
      if ($userImageData = UsersCore::getImageData($v['userId'])) {
        $v += $userImageData;
      }
    }
    return $r; 
  }
  
  /**
   * Устанавливает флаг на возможность редактирования сообщений
   *
   * @param   bool    Возможно редактирование
   */
  public function setEdit($flag) {
    $this->canEdit = $flag;
    if ($this->canEdit) $this->cond->removeFilter('active');
  }

  public function getActiveCond() {
    return $this->canEdit ? '1' : "{$this->table}.active = 1";
  }
  
  public $forceDublicatesCheck;
  
  public $dateCreate;
  
  public function create(array $data) {
    Arr::checkEmpty($data, 'text');
    if (!$text = trim($data['text'])) throw new NgnValidError('Пустой текст');
    // Защита от повторного постинга одного и того же сообщения одним пользователем
    if (!$this->forceDublicatesCheck) {
      foreach (db()->selectCol(
      "SELECT text FROM {$this->table} WHERE userId=?d ORDER BY id DESC LIMIT 3",
      $data['userId']) as $_text) {
        if ($_text == $text) throw new NgnValidError('Такое сообщение уже было только что добавлено');
      }
    }
    $data['text_f'] = $this->formatHTML($data['text'], 666);
    if (!empty($data['ansId']) and $ansMsg = $this->getMsg($data['ansId']))
      $data['ansUserId'] = $ansMsg['userId'];
    else $data['ansUserId'] = null;
    $data[$this->id1Field] = $this->id1;
    $data[$this->id2Field] = $this->id2;
    if (!isset($data['dateCreate'])) $data['dateCreate'] = dbCurTime();
    $data['dateUpdate'] = $data['dateCreate'];
    $data['ip'] = $_SERVER['REMOTE_ADDR'];
    $d = $data;
    unset($d['userGroupId']);
    $id = db()->insert($this->table, $d);
    // Добавляем записть в табличку для сортировки
    db()->insert('comments_srt', array(
      'id' => $id,
      'active' => 1,
      'parentId' => $this->id1,
      'id2' => $this->id2,
      'userGroupId' => !empty($data['userGroupId']) ? $data['userGroupId'] : 0
    ));
    $this->updateCount();
    $this->subscribeItem();
    $this->clearCache();
    return $id;
  }
  
  public function update($id, $text) {
    $textF = $this->formatHTML($text, $id);
    db()->query("
      UPDATE {$this->table} SET text=?, text_f=?, dateUpdate=?
      WHERE
        id=?d AND
        {$this->id1Field}=?d AND
        {$this->id2Field}=?d
        ",
      $text, $textF, dbCurTime(), $id, $this->id1, $this->id2);
    $this->clearCache();
  }
  
  public function activate($id) {
    $this->_activate($id, 1);
  }
  
  public function deactivate($id) {
    $this->_activate($id, 0);
  }
  
  public function _activate($id, $flag) {
    db()->query("
      UPDATE {$this->table} SET active=?d
      WHERE id=?d",
      $flag, $id, $this->id1, $this->id2);
    db()->query('UPDATE comments_str SET active=?d WHERE id=?d', $flag, $id);
    $this->updateCount();
    $this->clearCache();
  }
  
  public function delete($id) {
    db()->query("
      DELETE FROM {$this->table}
      WHERE id=?d AND {$this->id1Field}=?d AND {$this->id2Field}=?d",
      $id, $this->id1, $this->id2);
    db()->query('DELETE FROM comments_srt WHERE id=?d', $id);
    $this->updateCount();
    $this->clearCache();
  }
  
  public function updateCount() {
  }
  
  static public function formatHTML($text, $id = null) {
    $oFormatText = new FormatText(array(
      'allowedTagsConfigName' => 'comments.allowedTags'
    ));
    $oFormatText->oJevix->cfgSetAutoBrMode(true);
    return $oFormatText->html($text);
  }
  
  /**
   * Подписывает пользователя на уведомления о новых сообщениях в этой группе сообщений
   *
   * @param   integer   ID2
   * @param   integer   ID пользователя
   */
  function subscribe($userId) {
    Notify_SubscribeItems::update($userId, $this->subscribeType, $this->id1, $this->id2);
  }
  
  /**
   * Отписывает пользователя от уведомлений о новых сообщениях в этой группе сообщений
   *
   * @param   integer   ID2
   * @param   integer   ID пользователя
   */
  function unsubscribe($userId) {
    Notify_SubscribeItems::delete($userId, $this->subscribeType, $this->id1, $this->id2);
  }
  
  /**
   * Возвращает статус подписки на эти сообщения
   *
   * @param   integer   ID2
   * @param   integer   ID пользователя
   * @return  bool
   */
  function isSubscribed($userId) {
    return;
    return Notify_SubscribeItems::subscribed(
      $userId, $this->subscribeType, $this->id1, $this->id2);
  }  
  
  /**
   * Загружет изображение в соответствующую папку
   *
   * @param   integer ID сообщения
   * @param   array   Массив из $_FILES['image'] при загрузке одного файла
   */
  public function iiUpload($uploadFileData) {
    if (!$uploadFileData['tmp_name'] or !file_exists($uploadFileData['tmp_name'])) return false;
    $dir = UPLOAD_PATH.'/'.INLINE_IMAGES_DIR.'/'.$this->id2.'/';
    Dir::make($dir);
    $fname = 'ii'.rand(1, 10000).'-'.Misc::translate($uploadFileData['name'], true);
    $fpath = $dir.$fname;
    copy($uploadFileData['tmp_name'], $fpath);
    unlink($uploadFileData['tmp_name']);
    return UPLOAD_DIR.INLINE_IMAGES_DIR.'/'.$this->id2.'/'.$fname;
  }
  
  function subscribeItem() {
    //Notify_SubscribeItems::update(
      //Auth::get('id'), 'comments_newMsgs', $this->id1, $this->id2);
  }
  
  protected function clearCache() {
    return;
  }
  
}