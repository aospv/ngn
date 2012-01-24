<?php

class CtrlAdminPageMeta extends CtrlAdminPagesBase {

  static $properties = array(
    'title' => 'Мета-теги',
    'order' => 310,
    'class' => 'meta'
  );
  
  protected $allowSlavePage = false;
  
  protected $defaultAction = 'edit';
  
  public function action_edit() {
    if (empty($this->pageId)) throw new EmptyException('$this->pageId');
    $oF = new Form(new Fields($this->getMetaFields()));
    $data = db()->selectRow('SELECT * FROM pages_meta WHERE id=?d', $this->page['id']);
    $oF->setElementsData($data);
    if ($oF->isSubmittedAndValid()) {
      db()->query('REPLACE INTO pages_meta SET ?a', array_merge(
        array('id' => $this->page['id']),
        $oF->getData()
      ));
      $this->redirect();
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'pages/editSettings';
  }
  
  public function action_editItemMeta() {
    $itemId = $this->getParam(4);
    $page = DbModelCore::get('pages', $this->pageId);
    if (empty($page['strName'])) throw new EmptyException('$page[strName]');
    $oF = new Form(new Fields($this->getMetaFields()));
    $data = db()->selectRow('SELECT * FROM dd_meta WHERE itemId=?d AND strName=?',
      $itemId, $page['strName']);
    $r = $oF->setElementsData($data);
    if ($oF->isSubmittedAndValid()) {
      db()->query('REPLACE INTO dd_meta SET ?a',
        array(
          'itemId' => $itemId,
          'strName' => $page['strName'],
          'title' => $r['title'],
          'titleType' => $r['titleType'],
          'description' => $r['description'],
          'keywords' => $r['keywords']
        )
      );
      $this->redirect();
    }
    $this->d['form'] = $oF->html();    
    $this->d['tpl'] = 'pages/editSettings';
  }
  
  
  protected function getMetaFields() {
    return array(
      array(
        'title' => 'Значение тэга «title»',
        'name' => 'title',
      ),
      array(
        'title' => 'Тип заголовка страницы',
        'name' => 'titleType',
        'type' => 'select',
        'default' => 'add',
        'options' => array(
          'add' => 'Заменять только заголовок страницы в теге «title»',
          'replace' => 'Заменять всё значение тэга «title»',
        )
      ),
      array(
        'title' => 'Описание',
        'name' => 'description',
        'type' => 'textarea',
      ),
      array(
        'title' => 'Ключевые слова',
        'name' => 'keywords',
        'type' => 'textarea',
      ),
    );
  }
  
}
