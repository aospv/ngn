<?php

class FormBase extends Options2 {
  
  public $templates = array(
    'form' => '{input}',
    'headerOpen' => '<div class="clearfix hgrp hgrp_{id}{class}">',
    'headerClose' => '</div>',
    'input' => '<div class="element{rowClass}">{title}{input}{help}{error}</div>',
    'title' => '<p class="label"><span class="ttl">{title}</span>{required}<span>:</span></p>',
    'error' => '<div class="vafiellidation-advice">{error}</div>',
    'globalError' => '<div class="element errorRow padBottom"><div class="validation-advice">{error}</div></div>',
    'help' => '<div class="clear"><!-- --></div><div class="help"><small>{help}</small></div>',
    'required' => '<b class="reqStar">*</b>',
    'element' => '' // используется в ф-ии html(), если флаг $this->isDdddTpl = true
  );

  /**
   * Two-dimensional array containing all the web form elements.
   *
   * @var array
   */
  public $els;

  /**
   * Encryption type of the form. Switches to "multipart/form-data" when using
   * a file upload element.
   *
   * @var string
   */
  public $encType = '';
  
  /**
   * @var bool
   */
  public $htmlentities = true;

  /**
   * This will be true if any of the web form elements was submitted via POST.
   *
   * @var bool
   */
  public $_isSubmitted = false;

  /**
   * This will be true if throw new NgnException() was called at least once
   *
   * @var bool
   */
  public $hasErrors = false;

  /**
   * @var string Уникальный идентификатор формы
   */
  public $id = "frm1";

  /**
   * Определяет наличие обрамляющего группу полей тэга
   *
   * @var bool
   */
  public $isHeaderGroupTags = true;
  
  /**
   * Выключить отображение тега <form>
   * 
   * @var bool
   */
  public $disableFormTag = false;
  
  /**
   * Использовать dddd-шаблоны в $this->templates
   * 
   * @var bool
   */
  public $isDdddTpl = false;
  
  /**
   * Action value for FORM tag
   *
   * @var string
   */
  protected $action = null;
  
  /**
   * Типы элементов, перед которыми заголовочные элементы будут закрываться 
   * 
   * @var array
   */
  protected $closingHeaderTypes = array('submit');
  
  /**
   * @var Req
   */
  public $oReq;
  
  public function __construct(array $options = array()) {
    parent::__construct($options);
    if (isset($this->options['id'])) $this->id = $this->options['id'];
    $this->oReq = empty($this->options['oReq']) ? O::get('Req') : $this->options['oReq'];
    $this->initSubmit();
  }
  
  protected function initSubmit() {
    $this->_isSubmitted = !empty($this->oReq->p);
  }
  
  protected function elementExists($name) {
    return isset($this->els[$name]);
  }
  
  /**
   * @param  string  Имя поля
   * 
   * @return FieldEAbstract
   */
  public function getElement($name) {
    if (!isset($this->els[$name])) return false;
    return $this->els[$name];
  }
  
  public function getElements() {
    if (!isset($this->els)) throw new NgnException('Elements not initialized');
    return $this->els;
  }
  
  protected $globalError;
  
  public function globalError($message) {
    $this->lastError = $this->globalError = 'Ошибка: '.$message;
    $this->hasErrors = true;
  }
  
  public function isSubmitted() {
    return $this->_isSubmitted;
  }

  public function isSubmittedAndValid() {
    return $this->isSubmitted() and $this->validate();
  }
  
  protected function htmlFormOpen() {
    if (!$this->disableFormTag) {
      $html = '<form action="'.($this->action ? $this->action : $_SERVER['REQUEST_URI']).'"';
      if (!empty($this->encType)) $html .= ' enctype="'.$this->encType.'"';
      $html .= ' id="'.$this->id.'" method="post">';
      return $html;
    } else {
      return '';
    }
  }
  
  protected function htmlElementInput(FieldEAbstract $el, $input) {
    $element = str_replace('{input}', $input, $this->templates['input']);
    $element = str_replace('{name}', $el->options['name'], $element);
    $element = str_replace('{value}', is_array($el->options['value']) ?
      '' : $el->options['value'], $element);
    $element = str_replace('{id}', $el->options['id'], $element);
    $element = str_replace('{rowClass}', $this->htmlGetRowClassAtr($el), $element);
    return $element;
  }
  
  protected function htmlElementTitle(FieldEAbstract $el, $elHtml) {
    // Добавляем к лейблу поля знак 'required' если таковой имеется в шаблонах
    if (!empty($el->options['noTitle']) or empty($el->options['title']))
      return str_replace('{title}', '', $elHtml);
    // Если нет шаблона для вывода заголовка
    if (empty($this->templates['title']))
      return str_replace('{title}', $el->options['title'], $elHtml);
    $templateLabel = str_replace(
      '{required}',
      !empty($el->options['required']) ? $this->templates['required'] : '',
      $this->templates['title']
    );
    $elHtml = str_replace('{title}', $templateLabel, $elHtml);
    return str_replace('{title}', $el->options['title'], $elHtml);
  }
  
  protected function htmlElementError($el, $elHtml) {
    if (!empty($el->error)) {
      if (!strstr($elHtml, '{error}')) {
        // Если в шаблоне 'input' нет места для ошибки, заменяем ею лейбел
        $elHtml = str_replace("{label}", $el->error, $elHtml);
      } else {
        // Иначе заменяем строку "{error}" на ошибку
        $elHtml = str_replace('{error}', $this->templates['error'], $elHtml);
        $elHtml = str_replace('{error}', $el->error, $elHtml);
      }
    } else {
      $elHtml = str_replace('{error}', '', $elHtml);
    }
    return $elHtml;
  }
  
  protected function htmlElementHelp(FieldEAbstract $el, $elHtml) {
    if (!empty($el->options['help'])) {
      $help = str_replace("\n", "<br />", $el->options['help']);
      $elHtml = str_replace('{help}', $this->templates['help'], $elHtml);
    } else {
      $help = '';
    }
    return str_replace('{help}', $help, $elHtml);
  }
  
  protected function htmlGetRowClassAtr(FieldEAbstract $el) {
    $rowClassAtr = (empty($el->options['id']) ? '' : ' row_'.$el->options['id']).
      ' type_'.$el->type;
    if (!empty($el->error)) $rowClassAtr .= ' errorRow';
    if (!empty($el->options['rowClass'])) $rowClassAtr .= ' '.$el->options['rowClass'];
    return $rowClassAtr;
  }
  
  protected function htmlGetDefaultAtr($row) {
    return ' name="'.$row['name'].'" id="'.Misc::name2id($row['name']).'i"';
  }
  
  protected function htmlElement(FieldEAbstract $el) {
    if (is_a($el, 'FieldEHeaderAbstract')) {
      // Для хедеров всё совсем иначе
      return $this->htmlHeader($el);
    }
    if (is_a($el, 'FieldEEmpty')) {
      return $this->htmlHeaderGroupClose($el->options['depth']);
    }
    $input = $el->html();
    if (!empty($el->options['noRowHtml'])) {
      // Для этих типов будет выводиться чисто <INPUT>
      return $input;
    }
    if ($this->isDdddTpl) {
      $elHtml = St::dddd(
        $this->templates['element'],
        array_merge($el->options, array('input' => $input))
      );
    } else {
      $elHtml = $this->htmlElementInput($el, $input);
      $elHtml = $this->htmlElementError($el, $elHtml);
      $elHtml = $this->htmlElementTitle($el, $elHtml);
      $elHtml = $this->htmlElementHelp($el, $elHtml);
    }
    if (in_array($el->type, $this->closingHeaderTypes))
      $elHtml = $this->closeAllOpenedHeaders('closing type').$elHtml;
    return $elHtml;
  }
  
  protected $curHeaderId;
  protected $headerOpened = array();
  
  protected function htmlHeaderGroupClose($elementDepth, $comments = '') {
    // Закрываем контейнер группы
    if (!$this->headerOpened($elementDepth))
      throw new NgnException("Header depth={{$elementDepth}} alreay closed. ($comments). html: <pre>$f</pre>");
    if (!$this->isHeaderGroupTags) return '';
    $this->setHeaderOpened($elementDepth, false);
    return $this->templates['headerClose'].
      "<!-- Close fields group depth={{$elementDepth}} ($comments) -->";
  }
  
  protected function setHeaderOpened($elementDepth, $flag) {
    $this->headerOpened[$elementDepth] = $flag;
  }

  protected $visibleRowN;
  
  protected $js = '';
  
  /**
   * Возвращает HTML формы
   *
   * @return string
   */
  public function html() {
    $html = $this->htmlFormOpen();
    if ($this->globalError)
      $html .= str_replace('{error}', $this->globalError, $this->templates['globalError']);
    $this->visibleRowN = -1;
    $elsHtml = '';
    $jsTypesAdded = array();
    foreach ($this->els as $el) {
      if ($el->options['type'] == 'hidden') continue;
      $this->visibleRowN++;
      $elsHtml .= $this->htmlElement($el);
      if (($js = $el->js()) == '') continue;
      if ($el->type == 'js' or !in_array($el->type, $jsTypesAdded)) {
        $jsTypesAdded[] = $el->type;
        $this->js .= $js;
      }
    }
    $elsHtml .= $this->closeAllOpenedHeaders('end of elements');
    $elsHtml = $this->wrapCols($elsHtml);
    // Если были колонки, нужно их очистить
    foreach ($this->els as $el) {
      if ($el->options['type'] != 'hidden') continue;
      $elsHtml .= $this->htmlElement($el);
    }
    $html = $html.str_replace('{input}', $elsHtml, $this->templates['form']);
    if (!$this->disableFormTag) $html .= '</form>';
    return $html.$this->js();
  }
  
  protected function wrapCols($html) {
    if (!strstr($html, 'type_col')) return $html;
    $n = 0;
    foreach ($this->els as $v) if ($v['type'] == 'col') $n++;
    return preg_replace(
      '/(<\!-- Open fields(?:[^>]*)-->(?:.*)<\!-- Close fields(?:[^>]*)-->)/sm',
      '<div class="colSet colN'.$n.'">$1<div class="clear"><!-- --></div></div>',
      $html);
  }
  
  public $disableJs = false;
  
  protected function js() {
    if ($this->disableJs or $this->disableFormTag) return '';
    // Call "js..." methods
    foreach (get_class_methods($this) as $method) {
      if ($method != 'js' and substr($method, 0, 2) == 'js') {
        if (($c = $this->$method()) != '')
          $this->js .= "\n\n// ------- $method ------- \n\n".$c;
      }
    }
    if ($this->js != '') {
      $this->js = str_replace('<!--', '/*', str_replace('-->', '*/', $this->js)); 
      return "\n\n<div id=\"{$this->id}js\" class=\"inlineJs\" style=\"display:none\">{$this->js}</div>";
    }
    return '';
  }

  public function display() {
    print $this->html();
  }
  
  public function getValues() {
    if (!$this->_rows) return false;
    foreach ($this->_rows as $v)
      if ($v['value']) $values[$v['name']] = $v['value'];
    return $values;
  }
  
  protected function validate() {
    if (!$this->isSubmitted()) return;
    foreach ($this->els as $el) {
      /* @var $el FieldEAbstract */
      if (!$el->validate()) {
        if (empty($el->error)) throw new NgnException('error is empty. $el: '.getPrr($el));
        $this->lastError = $el->error." (type={$el->type})";
        $this->hasErrors = true;
      }
    }
    if (isset($this->globalError)) {
      $this->lastError = $this->globalError;
      $this->hasErrors = true;
    }
    return !$this->hasErrors;
  }
  
  public $lastError;
  
  public function getLastError() {
    return $this->lastError;
  }
  
  //////////////////////////////////////////////////
  /////////////// FormBaseExtended //////////////////
  //////////////////////////////////////////////////
  
  public $tplRequired = '&nbsp;<b style="color: #FF0000;">*</b>'; 
  
  protected $nameArray;
  
  /**
   * Устанавливает имя массива всех input полей
   *
   * @param string
   */
  public function setNameArray($nameArray) {
    $this->nameArray = $nameArray;
  }
  
  private $equality;
  
  public function setEquality($name, $value) {
    $this->equality[$name] = $value;
  }
  
  public function setAction($action) {
    $this->action = $action;
  }
  
  protected function headerOpened($elementDepth) {
    return !empty($this->headerOpened[$elementDepth]);
  }
  
  protected function closeAllOpenedHeaders($comments) {
    $html = '';
    if (($l = count($this->headerOpened)) != 0) {
      // Закрываем открытые заголовочные блоки начиная с большей глубины
      for ($depth=$l-1; $depth>=0; $depth--) {
        if ($this->headerOpened($depth)) {
          $html .= $this->htmlHeaderGroupClose($depth, $comments);
        }
      }
    }
    return $html;
  }
  
  public function createCloseHeaderGroup($depth) {
    $this->createElement(array(
      'type' => 'html',
      'html' => $this->htmlHeaderGroupClose($depth, 'direct close')
    ));
  }
  
  protected function htmlHeader(FieldEHeaderAbstract $el) {
    if (!$this->isHeaderGroupTags) return false;
    $this->curHeaderId = $el->options['id'];
    //if ($this->headerOpened($el->options['depth']))
      //$t = $this->htmlHeaderGroupClose($el->options['depth'], 'open header');
    $t = '';
    if ($el->options['depth'] == 0)
      $t = $this->closeAllOpenedHeaders('open header depth 0');
    $this->setHeaderOpened($el->options['depth'], true);
    $tt = str_replace('{id}', $el->options['id'], $this->templates['headerOpen']);
    $tt = str_replace('{class}', ' type_'.$el->type, $tt);
    return
      $t.
      "<!-- Open fields group depth={{$el->options['depth']}}, type={{$el->type}}, id={{$this->curHeaderId}} -->\n".
      $tt.
      $el->html();
  }
  
  protected $n = 1;
  
  /**
   * @param array
   * @return FieldEAbstract
   */
  public function createElement(array $d) {
    if (!empty($d['name']) and isset($this->nameArray))
      $d['name'] = $this->nameArray.'['.$d['name'].']'; // check
    if (empty($d['type'])) $d['type'] = 'text';
    if (empty($d['name'])) {
      $d['name'] = 'el'.$this->n;
      $this->n++;
    }
    if (isset($this->els[$d['name']])) {
      throw new NgnException(
        'Field with name "'.$d['name'].'" already exists in <b>$this->els</b>. $d: '.getPrr($d).' $this->els: ');//getPrr($this->els)
    }
    if (isset($d['maxlength']) and $d['maxlength'] == 0) unset($d['maxlength']);
    
    
    $this->els[$d['name']] = $el = FieldCore::get($d['type'], $d, $this);
    if (isset($el->inputType) and $el->inputType == 'file')
      $this->encType = 'multipart/form-data';
    return $el;
  }
  
  public function deleteElement($name) {
    Arr::dropCallback($this->els, function($v) use ($name) {
      $v->options['name'] == $name;
    });
  }
  
  public function getData() {
    $r = array();
    foreach ($this->getElements() as $name => $el) {
      if (!empty($el->options['noValue'])) continue;
      if (!empty($this->options['filterEmpties']) and $el->isEmpty()) continue;
      $value = $el->value();
      BracketName::setValue($r, $name, $value === null ? '' : $value);
    }
    return $r;
  }

}
