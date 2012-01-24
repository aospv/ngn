<?php

abstract class FieldEFieldSetAbstract extends FieldEAbstract {

  protected $requiredOptions = array('name');
  
  public $options = array(
    'noRowHtml' => true,
    'noValue' => true,   
   // значит поле не создает само никаких данных (по его имени),
                          // но тем не менее может эти данные создавать и соответственно
                          // возвращаться функцией value()
    'filterEmpties' => true
  );
  
  protected $fields;
  
  protected $els = array();
  
  public function __construct(array $options = array(), Form $oForm = null) {
    parent::__construct($options, $oForm);
  }
  
  protected function init() {
    parent::init();
    $files = BracketName::getValue($this->oForm->oReq->files, $this->options['name']);
    if (empty($this->options['value']) and $files == null) {
      foreach (Arr::get($this->options['fields'], 'name') as $key)
        $emptyValue[$key] = '';
      $this->options['value'][] = $emptyValue;
    }
    $depth = ++$this->options['depth'];
    $this->oForm->createElement(array(
      'type' => empty($this->options['headerToggle']) ? 'header' : 'headerToggle',
      'depth' => $depth,
      'title' => isset($this->options['title']) ? $this->options['title'] : ''
    ));
    $this->oForm->createElement(array(
      'type' => 'html',
      'html' => '<div class="fieldSet type_'.$this->type.'">'
    ));
    $oFields = new Fields($this->options['fields']);
    // Генерируем поля по данным, если значение определено
    if (!empty($this->options['value'])) {
      // $this->options['value'] - значение взятое из поста
      $itemKeys = array_keys($this->options['value']);
    } elseif ($files !== null) {
      // в случае, если в форме только поля файлов
      $itemKeys = array_keys($files);
    }
    if (isset($itemKeys)) {
      foreach ($itemKeys as $n) {
        $this->oForm->createElement(array(
          'type' => 'header',
          'depth' => $depth+1
        ));
        foreach ($oFields->getFields() as $v) {
          $name = $v['name'];
          $v['name'] = $this->getName($n, $name);
          $v['value'] = $oFields->isFileType($name) ?
            BracketName::getValue($this->oForm->defaultData, $v['name']) :
            BracketName::getValue($this->oForm->elementsData, $v['name']);
          BracketName::setValue(
            $this->oForm->elementsData,
            $v['name'],
            $this->oForm->createElement($v)->value()
          );
        }
        $this->oForm->createElement(array('type' => 'headerClose', 'depth' => $depth+1));
      }
    } else {
      throw new NgnException('this block is not realized');
      // Или выводим одну группу полей, если не определено
      $this->oForm->createElement(array(
        'type' => 'header',
        'depth' => $depth+1
      ));
      // Пост с формы должен обязательно содержать массив с именем филдсета, кол-во элементов
      // в котором было бы равно кол-ву элементов филдсета на html-форме. Необходимо в случае,
      // если филдсет содержит только элементы файлов
      $this->oForm->createElement(array(
        'type' => 'hidden',
        'name' => $this->options['name'].'[dummy][0]',
        'value' => 1
      ));
      foreach ($this->options['fields'] as $v) {
        $v['name'] = $this->getName(0, $v['name']);
        $v['depth'] = $depth;
        $this->oForm->createElement($v);
      }
      $this->oForm->createElement(array('type' => 'headerClose', 'depth' => $depth+1));
    }
    $this->oForm->createElement(array(
      'type' => 'html',
      'html' => '<div class="clear"><!-- --></div></div>'
    ));
    $this->oForm->createElement(array('type' => 'headerClose', 'depth' => $depth));
  }
  
  public function _js() {
    $opts = Arr::filter_by_keys($this->options, array(
      'addTitle',
      'deleteTitle',
      'cleanupTitle'
    ));
    $opts['rowElementSelector'] = '.hgrp';
    return "
var id = '{$this->oForm->id}';
Ngn.Form.forms[id].eForm.getElements('.type_{$this->type}').each(function(el){
  new Ngn.frm.FieldSet(Ngn.Form.forms[id], el, ".Arr::jsObj($opts).");
});
";
  }
  
  abstract protected function getName($n, $name);

}