<?php

/**
 * Класс отвечает за получение и назначение привилегий пользователей
 * 
 * Всего существует 2 типа привилегий:
 * 1) Привилегии для разделов сайта
 * 2) Привилегии для полей динамических данных
 * 
 * Первые определяют разрешения пользователя на создание, измнение записей в 
 * определённом разделе. Привилегий привязываются к ID раздела.
 * Например: edit, означает, что допускается изменять все записи в текущем разделе
 * 
 * Вторые определяют разрешения на создание, изменение данных конкретного поля.
 * Привилегии привязываются к имени поля.
 * Название привилегии 2-го типа должно иметь следующий формат: dd_[action]_[fieldName]
 * Например: dd_edit_subjects, означение что допускается изменять и добавлять данные
 * этого поля.
 * 
 */
class Privileges {
  
  /**
   * Массив с привилегиями текущего авторизованого пользователя для указанного раздела
   *
   * @var array
   */
  public $priv;
  
  /**
   * Массив со всеми привилегиями для указанного раздела
   *
   * @var array
   */
  private $pagePriv;
  
  /**
   * Привилегиии, которые назначаются при наличии других привилегий.
   * Ключ массива - определяющая привилегия
   * Подмассив - назначеемые привилегии
   *
   * @var array
   */
  public $subPriv = array(
    'edit' => array('create', 'view'),
    'sub_edit' => array('sub_create')
  );
  
  static public function check($userId, $pageId, $type) {
    $r = db()->selectRow(
      "SELECT * FROM privs WHERE serId=?d AND pageId=?d AND type=?",
      $userId, $pageId, $type);
    return empty($r);
  }
  
  public function &setPagePriv($pageId) {
    if ($this->pagePriv) return $this->pagePriv;
    $priv = array();
    foreach (db()->select("SELECT userId, type
      FROM privs WHERE pageId=?d", $pageId) as $k => $v) {
      $priv[$v['userId']][$v['type']] = 1;
    }
    $this->pagePriv = $priv;
    return $this->pagePriv;
  }
  
  public function set($userId, $pageId) {
    $priv = $this->setPagePriv($pageId);
    // Записываем привилегии сначало для всех пользователей, если они определены
    // и если текущей пользователь авторизован
    if (Auth::get('id') and isset($priv[REGISTERED_USERS_ID]))
      $this->priv = $priv[REGISTERED_USERS_ID];
    elseif (isset($priv[ALL_USERS_ID])) { // Для всех пользователь записываем привилегии в любом случае
      $this->priv = $priv[ALL_USERS_ID];
    } // Добавляем к ним привилегии для конкретного пользователя, если они определены
    if (isset($priv[$userId])) {
      if ($this->priv) $this->priv += $priv[$userId];
      else $this->priv = $priv[$userId];
    }    
    return $this->priv;
  }

  /**
   * Определяет назначены ли привилегии для заданного раздела
   *
   * @param   integer   ID раздела
   * @return  bool
   */
  public function isPagePriv($pageId) {
    return $this->setPagePriv($pageId) ? true : false;
  }

  /**
   * Добавляет в массив записей флаг canEdit, если эта запись принадлежит указанному 
   * пользователю и если с момента её создания прошло достаточно времени. 
   * Допустимое кол-во секунд для редактирования записи указывается параметром $seconds.
   * Массив записи должен обязательно иметь значение dateCreate_tStamp - время создания
   * в TIMESTAMP формате.
   *
   * @param   array   Массив за записями
   * @param   integer ID пользователя, для которого проставляются права на редактирование
   * @param   integer Время в секундах в течении которого допускается редактирование 
   *                  записи начиная с момента создания
   */
  static function extendItemsPriv(&$items, $userId, $seconds) {
    foreach ($items as &$v) {
      self::extendItemPriv($v, $userId, $seconds);
    }
  }

  /**
   * Добавляет в массив записи флаг canEdit, если эта запись принадлежит указанному 
   * пользователю и если с момента её создания прошло достаточно времени. 
   * Допустимое кол-во секунд для редактирования записи указывается параметром $seconds.
   * Массив записи должен обязательно иметь значение dateCreate_tStamp - время создания
   * в TIMESTAMP формате.
   *
   * @param   array   Массив за записями
   * @param   integer ID пользователя, для которого проставляются права на редактирование
   * @param   integer Время в секундах в течении которого допускается редактирование 
   *                  записи начиная с момента создания
   */
  static function extendItemPriv(&$item, $userId, $seconds) {
    if (!$userId) return false;
    if (!isset($item['dateCreate_tStamp']))
      throw new NgnException("\$item['dateCreate_tStamp'] not defined. \$item: ".getPrr($item));
    if ($item['userId'] == $userId and $item['dateCreate_tStamp'] + $seconds > time()) {
      $item['canEdit'] = true;
      $item['expires'] = $item['dateCreate_tStamp'] + $seconds - time();
      return true;
    }
    return false;
  }
  
  /**
   * Получает список ID пользователей с этой привилегией для этого раздела
   *
   * @param   integer   ID раздела
   * @param   string    Тип привилегии
   */
  static function getUserIds($pageId, $type) {
    return db()->selectCol(
      "SELECT userId FROM privs WHERE pageId=?d AND type=?",
      $pageId, $type);
  }
  
  public function getUsers($pageId, $type) {
    return db()->select("
      SELECT u.login, p.userId FROM privs AS p, users AS u 
      WHERE p.userId=u.id AND p.pageId=?d AND p.type=? LIMIT 10",
      $pageId, $type);
  }
  
  /**
   * Возвращает массив со всеми существующими для назначения привилегиями, 
   * где ключ массива это имя привилегии, а значение - её название
   *
   * @return array
   */
  public function getTypes() {
    $types = array(
      'dummy' => 'dummy',
      'create' => 'create',
      'edit' => 'edit',
      'view' => 'view',
      'sub_edit' => 'sub_edit',
      'sub_create' => 'sub_create',
      'moder' => 'moder'
    );
    return $types;
  }
  
}