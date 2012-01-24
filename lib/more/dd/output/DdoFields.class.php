<?php

class DdoFields extends Options {

  /**
   * Массив с именами полей, разрешенными для вывода
   *
   * @var array
   */
  private $allowedFields;

  /**
   * Имя лейаута полей
   *
   * @var string
   */
  private $layoutName;
  
  /**
   * Получать все поля, без учета настроек фильтра $allowedFields
   *
   * @var bool
   */
  public $getAll = false;
  
  /**
   * Определяет выводит ли текущий класс список записей или одну запись
   *
   * @var string
   */
  public $isItemsList = true;
  
  /**
   * Флаг определяет существуют ли настройки фильтра для текущего лейаута
   *
   * @var bool
   */
  protected $settingsExists;
  
  /**
   * Объект полей
   *
   * @var DdFields
   */
  protected $oFields;
  
  /**
   * @var DdoSettings
   */
  protected $oSettigns;
  
  /**
   * Дополнительные виртуальные поля, такие как "количество комментариев",
   * "мозаика альбома"  и п.т.
   *
   * @var array
   */
  protected $extraVirtualFields = array(
    'commentsCount' => array(
      'name' => 'commentsCount',
      'oid' => 200,
      'title' => 'Количество комментариев',
      'descr' => 'Ссылка на комментарии с цифрой их количества',
      'type' => 'commentsCount',
      'extraVirtual' => true
    ),
    'clicks' => array(
      'name' => 'clicks',
      'oid' => 300,
      'title' => 'Количество просмотров',
      'type' => 'clicks',
      'extraVirtual' => true
    ),
    'author' => array(
      'name' => 'author',
      'oid' => 400,
      'title' => 'Автор',
      'type' => 'author',
      'extraVirtual' => true
    )
  );
  
  /**
   * @param   DdoSettings  Объект настроек лейаута
   * @param   string  Имя лейаута
   */
  public function __construct(DdoSettings $oDdoSettings, $layoutName, $strName, array $options = array()) {
    if (!$layoutName) throw new NgnException('$layoutName not defined');
    $this->setOptions($options);
    $this->oFields = new DdFields($strName, array(
      'getDisallowed' => true,
      'getSystem' => true,
    ));
    $this->layoutName = $layoutName;
    $this->oSettigns = $oDdoSettings;
    $allowedFields = $oDdoSettings->getAllowedFields($layoutName);
    if ($allowedFields) {
      $this->allowedFields = $allowedFields;
      $this->settingsExists = true;
    } else {
      $this->settingsExists = false;
    }
  }

  /**
   * Эти типы не должны выводиться по-умолчанию
   *
   * @var array
   */
  protected $forceListShowTypes = array(
    'wisiwig',
    'typoTextarea'
  );
  
  protected $forceShowTypes = array(
    'ddItemsSelect'
  );
  
  public function getFields() {
    $fields = $this->oFields->getFields();
    $fields += $this->extraVirtualFields;
    $_fields = array();
    foreach ($fields as $k => $v) {
      if (!empty($v['virtual'])) continue;
      if (!empty($v['notList'])) continue;
      if (empty($this->options['getAll'])) {
        // Если настройки не определены
        if (!$this->settingsExists) {
          // Не выводим системные по умолчанию
          if (!empty($v['system']) or !empty($v['extraVirtual'])) continue;
          // Не выводим большие текстовые поля для списков записей
          if ($this->isItemsList and in_array($v['type'], $this->forceListShowTypes)) continue;
          if (in_array($v['type'], $this->forceShowTypes)) continue;
        }
        if (!$this->allowed($v['name'])) continue;
      }
      $_fields[$k] = $v;
    }
    $this->order($_fields);
    return $_fields;
  }
  
  private function order(&$fields) {
    if (($order = $this->oSettigns->getOrder($this->layoutName)) === false) return;
    foreach ($fields as $k => &$v)
      if (isset($order[$v['name']]))
        $fields[$k]['oid'] = $order[$v['name']];
    $fields = Arr::sort_by_order_key($fields, 'oid');
  }
  
  private function allowed($fieldName) {
    if (!$this->allowedFields) return true;
    return in_array($fieldName, $this->allowedFields);
  }
  
}