<?php

/**
 * 
 * Каждая из частей этого поля срабатывает в разные моменты
 * 
 * 1. init(), например при создании поля, тогда, когда значение поля ещё не заполнено
 * в этом случае можно определить значения по умолчанию.
 * В случае же с файлом в init() происходит заполнение конечного значения поля из массива _FILES,
 * т.к. в _POST этого значения нет.
 * Значение поля находится в $this->options['value']
 *
 */
abstract class FieldEAbstract extends Options2 implements ArrayAccess {

  public $type;
  
  public $error;
  
  /**
   * @var Form
   */
  protected $oForm;
  
  protected $allowedFormClass;
  
  public $valueChanged = false;
  
  protected $defaultValue;
  
  public $isPostValue = false;
  
  protected $useDefaultJs = false;
  
  public function __construct(array $options = array(), Form $oForm = null) {
    $this->oForm = $oForm;
    if (isset($this->allowedFormClass)) {
      if (!isset($this->oForm))
        throw new NgnException('$this->oForm must be defined');
      if (!is_a($this->oForm, $this->allowedFormClass))
        throw new NgnException(
          'Form object must be an instance of "'.$this->allowedFormClass.'" class');
    }
    // default options
    $this->options['depth'] = 0;
    parent::__construct($options);
    if (!empty($this->options['name'])) {
      $this->isPostValue = (BracketName::getValue(
        $this->oForm->oReq->p, $this->options['name'], BracketName::modeFalse) !== false);
    }
    if ($this->isPostValue) $this->preparePostValue();
    $this->initDefaultValue();
  }
  
  protected function preparePostValue() {}
  
  protected function prepareValue() {
    if (!empty($this->options['noValue'])) return;
    if ($this->isEmpty() and isset($this->options['default']))
      $this->options['value'] = $this->options['default'];
    if (!empty($this->options['value']) and is_string($this->options['value']))
      $this->options['value'] = trim($this->options['value']);
  }
  
  protected $staticType;
  
  protected function init() {
    if (empty($this->options['id']))
      $this->options['id'] = Misc::name2id($this->options['name']);
    if ($this->isEmpty() and empty($this->options['value'])) {
      $this->options['value'] = null;
    }
    $this->prepareValue();
    if (isset($this->staticType))
      $this->type = $this->staticType;
    else
      $this->type = lcfirst(Misc::removePrefix('FieldE', get_class($this)));
    if ($this->oForm->isSubmitted()) {
      if ($this->oForm->create) {
        $this->valueChanged = true;
      } else {
        $defValue = BracketName::getValue($this->oForm->defaultData, $this->options['name']);
        // Если поле до поста было не пустым
        if ($defValue !== null and $defValue != $this->options['value']) {
          $this->valueChanged = true;
        }
      }
    }
    $this->addRequiredCssClass();
  }
  
  protected function addRequiredCssClass() {
    if (!empty($this->options['required']))
      $this->cssClasses[] = 'required';
  }
  
  /**
   * Возвращает текущее значение поля
   */
  public function value() {
    if (!empty($this->options['noValue'])) return null;
    return isset($this->options['value']) ? $this->options['value'] : null;
  }
  
  public function titledValue() {
    return $this->value();
  }
  
  public function isEmpty() {
    if (!isset($this->options['value'])) return true;
    return Arr::is_empty($this->options['value']);
  }

  /**
   * Вызывается только при сабмите формы
   */
  public function validate() {
    if (!empty($this->error)) return false;
    $n = 1;
    if (!empty($this->options['validator'])) {
      foreach (Misc::quoted2arr($this->options['validator']) as $name) {
        if (($error = O::get(ClassCore::nameToClass('FieldV', $name))->error($this->options['value'])) !== false) {
          $this->error = $error;
        }
      }
    }
    $method = 'validate'.$n;
    while (method_exists($this, $method)) {
      if ($n > 1 and empty($this->options['value'])) break;
      $this->$method();
      if (!empty($this->error)) break;
      $n++;
      $method = 'validate'.$n;
    }
    return empty($this->error);
  }
  
  protected function validate1() {
    if (!empty($this->options['required']) and empty($this->options['value'])) {
      $this->error = "Поле «{$this->options['title']}» обязательно для заполнения";
    }
  }
  
  public function error($text) {
    $this->error = $text;
  }
  
  protected function initDefaultValue() {}
  
  public function html() {
    if (isset($this->defaultValue) and !isset($this->options['value']))
      $this->options['value'] = $this->defaultValue;
    return $this->_html();
  }
  
  public function _html() {
    return '';
  }
  
  public function js() {
    $js = '';
    if (!empty($this->options['jsOptions']))
      $js .= 'Ngn.Form.ElOptions.'.$this->options['name'].' = '.Arr::jsObj($this->options['jsOptions'])."\n";
    return "\n\n<!--{$this->type}-->\n".$js.$this->_js();
  }
  
  public function _js() {
    if ($this->useDefaultJs) return $this->defaultJs();
    return '';
  }
  
  protected $cssClasses = array();
  
  protected function getCssClasses() {
    if (isset($this->options['cssClass']))
      return array_merge($this->cssClasses, array($this->options['cssClass']));
    return empty($this->cssClasses) ? false : $this->cssClasses;
  }
  
  protected function defaultJs() {
    return "\nnew Ngn.Form.ElInit.factory(Ngn.Form.forms.{$this->oForm->id}, '{$this->type}');\n";
  }
  
  // ------------ array access -----------

  public function offsetSet($offset, $value) {
    if (is_null($offset)) {
      $this->options[] = $value;
    } else {
      $this->options[$offset] = $value;
    }
  }
  
  public function offsetExists($offset) {
    return isset($this->options[$offset]);
  }
  
  public function offsetUnset($offset) {
    unset($this->options[$offset]);
  }
  
  public function offsetGet($offset) {
    return isset($this->options[$offset]) ? $this->options[$offset] : null;
  }
  
}
