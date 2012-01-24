<?php

class Form extends FormBase {

  /**
   * @var Fields
   */
  public $oFields;

  public $options = array(
    'submitTitle' => 'Сохранить'
  );

  /**
   * Если флаг включен в форме будут выводится только обязательные поля
   *
   * @var bool
   */
  public $onlyRequired = false;

  public $disableSubmit = false;

  /**
   * @var FormSpamBotBlocker
   */
  public $oFSBB;

  public $nospam;

  public $enableFSBB = true;

  //public $enableCaptcha = false;
  
  public $elementsData = array();

  public $defaultData = array();
  
  public $disableFormTag = false;
  
  public $create = false;
  
  protected function defineOptions() {
    $this->options['id'] = 'f'.Misc::randString(3, true);
  }
  
  public function __construct(Fields $oFields, array $options = array()) {
    parent::__construct($options);
    $this->oFields = $oFields;
  }
  
  protected function init() {}
  
  private function initFSBB() {
    // Init FormSpamBotBlocker
    if ($this->enableFSBB) {
      if ($this->oFSBB) return;
      $this->oFSBB = new FormSpamBotBlocker();
      $this->oFSBB->hasSession = false;
      $this->nospam = false; // Если FSBB включен, определяем включаем флаг отсутствия спама
    } else {
      $this->nospam = true;
    }
  }

  // Action Field
  protected $defaultActionName = 'action';

  protected $hiddenFieldsData = array();

  protected $actionFieldValue;

  public function setActionFieldValue($v) {
    $this->actionFieldValue = $v;
  }

  public function addHiddenField($data) {
    if (!isset($data['name']))
      throw new NgnException('Name not defined in: '.getPrr($data));
    $data['type'] = 'hidden';
    $this->hiddenFieldsData[] = $data;
  }

  protected $defaultElementsDefined = false;
  
  protected function initDefaultElements() {
    if ($this->defaultElementsDefined) return;
    if ($this->disableFormTag) return;
    $this->addHiddenField(array(
      'name' => 'formId',
      'value' => $this->id,
      'noValue' => true
    ));
    if (!empty($this->actionFieldValue)) {
      $this->addHiddenField(
        array(
          'name' => $this->defaultActionName, 
          'value' => $this->actionFieldValue,
          'noValue' => true
        ));
    }
    if (isset($_SERVER['HTTP_REFERER'])) {
      $this->addHiddenField(
        array(
          'name' => 'referer', 
          'value' => $_SERVER['HTTP_REFERER'],
          'noValue' => true
        ));
    }
    foreach ($this->hiddenFieldsData as $v) {
      $this->createElement($v);
    }
    $this->defaultElementsDefined = true;
    if (!empty($this->defaultElements)) foreach ($this->defaultElements as $v) {
      $this->createElement($v);
    }
  }
  
  public $defaultElements;

  protected $noDataTypes = array(
    'html'
  );
  
  /**
   * Определяет откуда пришли данные для формы, из HTTP запроса или напрямую через массив
   * Это необходимо, что бы в файловых полях не использовать массив _FILES в случае без запроса
   * 
   * @var bool
   */
  public $fromRequest = true;
  
  protected function initElements() {
    $this->hasErrors = false;
    $this->els = array();
    if ($this->onlyRequired) {
      $fields = $this->oFields->getRequired();
    } else {
      // Здесь необходимо использовать getFieldsF, потому что она возвращает только видимые поля,
      // без системных и скрытых. А по идее там осуществляются вские ненужные операции.. кажется
      // + ещё права там проверяются
      $fields = $this->oFields->getFieldsF();
    }
    foreach ($fields as $v) {
      // файлов нужно обязательно использовать
      if ($this->oFields->isFileType($v['name'])) {
        $value = BracketName::getValue($this->defaultData, $v['name'], BracketName::modeString);
      } else {
        $value = BracketName::getValue($this->elementsData, $v['name'], BracketName::modeNull);
      }
      if ($value !== null) $v['value'] = $value;
      BracketName::setValue($this->elementsData, $v['name'], $this->createElement($v)->value());
    }
    if (!$this->disableSubmit) {
      $this->createElement(array(
        'value' => $this->options['submitTitle'],
        'type' => 'submit'
      ));
    }
  }
  
  /**
   * Генерирует поля и возвращает их значения
   *
   * @param   array   Значения по умолчанию
   * @return  array
   */
  public function setElementsData(array $defaultData = array()) {
    $this->defaultData = $defaultData;
    $this->elementsData = $defaultData;
    if ($this->isSubmitted() and $this->fromRequest) $this->elementsData = $this->oReq->p;
    $this->initElements();
    $this->validate();
    return $this;
  }
  
  protected $elementsDefaultDefined = false;
  
  /**
   * Функция вызывается при рендеренге формы, если поля не были 
   * определены ф-ей setFieldsData()
   */
  protected function setElementsDataDefault() {
    if ($this->elementsDefaultDefined) return;
    $this->setElementsData($this->defaultData);
    $this->elementsDefaultDefined = true;
  }

  private $fieldsEquality;

  public function setFieldsEquality($name1, $name2) {
    $this->fieldsEquality[$name1] = $name2;
  }

  /**
   * Выводит только указанные для инициализации поля
   *
   * @param   bool  Флаг
   */
  public function outputOnlyFields($flag = true) {
    $this->disableFormTag = $flag;
    $this->disableSubmit = $flag;
    $this->disableJs = $flag;
  }

  public function getFields() {
    return $this->oFields->getFieldsF();
  }

  public function fsbb() {
    if ($this->hasErrors) return;
    $this->initFSBB();
    // Проверяем на спам, если есть сабмит и добавляем ошибку, если проверку не прошла
    if ($this->enableFSBB and $this->_isSubmitted) {
      // Ах да.. только в том случае, если засабмичено
      //if ($this->defaultData) throw new NgnException('default data not exists');
      $this->nospam = $this->oFSBB->checkTags($this->defaultData);
      if (!$this->nospam) {
        $this->globalError(
          'Не прошла проверка на спам. <a href="'.Tt::getPath().'">Попробуйте заполнить форму ещё раз</a>');
      }
    }
  }

  public function html() {
    $this->setElementsDataDefault();
    $this->initDefaultElements();
    if ($this->disableSubmit) {
      foreach ($this->els as $k => $el)
        if ($el->type == 'submit')
          unset($this->els[$k]);
    }
    $html = parent::html();
    $html = str_replace('</form>', $this->htmlVisibilityConditions().'</form>', $html);
    if (isset($this->oFSBB)) {
      $html = str_replace('</form>', $this->oFSBB->makeTags().'</form>', $html); 
    }
    return $html;
  }
  
  
  // ====================== Visibility Conditions ================

  protected $visibilityConditions = array();
  
  /**
   * Добавляет условие видимости определенных секций.
   * Секцией называется html-элемент вида <div class="hgrp hgrp_headerName">,
   * с которого начинается заголовочное поле, открывающее секцию.
   * 
   * Добавляемые условия используются в javascript'е для динамического
   * отображения и скрытия секций.
   * 
   * @param   string  Имя заголовочного поля, открывающее секцию
   * @param   string  Имя поля, от которого зависит отображать ли секцию
   * @param   string  Условие отображения в формате "$v == 4",
   *                  где $v - текущее значение поля $condFieldName
   * 
   */
  public function addVisibilityCondition($headerName, $condFieldName, $cond) {
    $this->visibilityConditions[] = array(
      'headerName' => $headerName,
      'condFieldName' => $condFieldName,
      'cond' => $cond
    );
  }
  
  /*
  protected function jsVisibilityConditions() {
    if (empty($this->visibilityConditions)) return '';
    foreach ($this->visibilityConditions as $v)
      $s .= "Ngn.frm.visibilityCondition(
eForm, '{$v['headerName']}', '{$v['condFieldName']}', '{$v['cond']}');";
    return $s;
  }
  */
  
  protected function htmlVisibilityConditions() {
    if (empty($this->visibilityConditions)) return '';
    foreach ($this->visibilityConditions as $v)
      $r[] = array(
        $v['headerName'],
        $v['condFieldName'],
        $v['cond']
      );
    return '<div class="visibilityConditions" style="display:none">'.json_encode($r).'</div>';
  }
  
  protected function initSubmit() {
    $this->_isSubmitted = isset($this->oReq->p['formId']);
  }
  
  public function isSubmittedAndValid() {
    $this->setElementsDataDefault();
    if (!parent::isSubmittedAndValid()) return false;
    return true;
  }
  
  // Функционал для апдейта данных через класс формы
  
  public function update() {
    if (!$this->isSubmittedAndValid()) return false;
    $this->_update($this->getData());
    return true;
  }
  
  protected function _update(array $data) {}
  
  public function isSubmitted() {
    return $this->fromRequest ? parent::isSubmitted() : true;
  }
  
  public function debugElements() {
    $this->setElementsDataDefault();
    foreach ($this->els as $el) prr($el->options);
    die2('======');
  }
  
  public $tinyInitialized = false;
  
  protected function jsUpload() {
    $opt = empty($this->options['uploadOptions']) ?
      '' : Arr::jsObj($this->options['uploadOptions']);
    return "
(function() {
  Ngn.Form.forms.{$this->id}.initUpload($opt);
}).delay(100);
";
  }
  
  /**
   * Добавляет информацию об кол-ве оставшихся знаков для ввода
   */
  protected function jsMaxLength() {
    return "
Ngn.frm.maxLength($('{$this->id}'), ".FieldEInput::defaultMaxLength.");
";
  }
  
  public function getTitledData() {
    $r = array();
    foreach ($this->getElements() as $name => $el) {
      if (!empty($el->options['noValue'])) continue;
      if (!empty($this->options['filterEmpties']) and $el->isEmpty()) continue;
      $r[$name] = array(
        'title' => $this->oFields->fields[$name]['title'],
        'value' => $el->titledValue()
      );
    }
    return $r;
  }
  
  public function hasAttachebleWisiwig() {
    return Arr::getValueByKey($this->oFields->fields, 'type', 'wisiwig') !== false;
  }
  
}
