<?php

class CtrlAdminDdField extends CtrlAdmin {

  static $properties = array(
    'title' => 'Поля',
    'descr' => 'Поля структур',
    'onMenu' => false
  );

  protected $prepareMainFormTpl = true;

  /**
   * Информация о текущей структуре
   * 
   * @var array
   */
  public $strData;

  /**
   * Имя структуры
   *
   * @var string
   */
  public $strName;

  /**
   * @var DdFieldsManager
   */
  protected $oIM;

  protected function init() {
    if (!$this->params[2])
      throw new NgnException('Структура не определена');
    $this->d['tpl'] = 'ddField/default';
    $oI = new DbItems('dd_structures');
    if (!$this->strData = $oI->getItemByField('name', $this->params[2])) {
      throw new NgnException('Структура не определена');
    }
    $this->d['strData'] = $this->strData;
    $this->strName = $this->strData['name'];
    $this->oIM = new DdFieldsManager($this->strName);
  }

  public function action_default() {
    $this->oIM->oItems->cond->addF('strName', $this->strName);
    $this->d['items'] = $this->oIM->oItems->getItems();
    $this->setPageTitle(
      'Редактирование полей структуры «'.$this->d['strData']['title'].'»');
  }
  
  public function action_new() {
    if ($this->oIM->requestCreate()) {
      $this->redirect(Tt::getPath(3));
    }
    $this->d['form'] = $this->oIM->oForm->html();
    $this->setPageTitle(
      'Создание поля структуры «'.$this->d['strData']['title'].'»');
  }
  
  public function action_edit() {
    $fieldData = $this->oIM->oItems->getItem($this->oReq->rq('id'));
    if ($this->oIM->requestUpdate($this->oReq->rq('id'))) {
      $this->redirect(Tt::getPath(3));
    }
    $this->d['form'] = $this->oIM->oForm->html();
    $this->setPageTitle(
      'Редактирование поля «'.$fieldData['title'].'» структуры «'.
      $this->d['strData']['title'].'»');
  }

  public function action_delete() {
    $this->oIM->delete($this->oReq->rq('id'));
    $this->redirect();
  }

  public function action_ajax_delete() {
    $this->oIM->delete($this->oReq->rq('id'));
  }

  public function action_ajax_reorder() {
    DbShift::items($this->oReq->rq('ids'), 'dd_fields');
  }

  public function action_import() {
    $this->d['tpl'] = 'ddField/import';
    $this->setPageTitle('Импорт полей');
  }

  public function action_ajax_importPreview() {
    if (!$this->oReq->r['text'])
      return;
    if (!$fields = $this->text2fields($this->oReq->r['text']))
      return;
    $oF = new Form(new Fields($fields));
    print $oF->html();
  }

  public function action_ajax_importMake() {
    $this->text2fields($this->oReq->rq('text'), false);
    print "Перейдите к просмотру... раздела с этой формой";
  }

  public function text2fields($text, $onlyGet = true) {
    $tree = O::get('common/Text2Tree')->getTree($text);
    /* @var $oF DdFields */
    $oF = O::get('DdFields', $this->strName);
    $n = 0;
    foreach ($tree as $v) {
      $n++;
      if (preg_match('/(.+)(?:\[(.+)\])/i', $v['title'], $m)) {
        $title = mb_strtolower(trim($m[1]), CHARSET);
        $type = mb_strtolower(trim($m[2]), CHARSET);
        $type = $oF->types[$type] ? $type : 'radio';
      } else {
        $title = trim($v['title']);
        $type = 'radio';
      }
      if ($onlyGet) {
        $fields[] = array(
          'name' => 'f' . $n, 
          'title' => $title, 
          'type' => $type, 
          'options' => isset($v['children']) ? Arr::get($v['children'], 'title') : array()
        );
      } else {
        $oF->create(
          array(
            'name' => 'f' . $n, 
            'oid' => $n * 10, 
            'title' => $title, 
            'type' => $type, 
            'valuesList' => implode("\n", 
              isset($v['children']) ? Arr::get($v['children'], 
                'title') : array())
          ));
      }
    }
    if ($onlyGet)
      return $fields;
  }

  public function action_deleteAll() {
    $this->redirect();
  }

}
