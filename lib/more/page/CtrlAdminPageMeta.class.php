<?php

class CtrlAdminPageMeta extends CtrlAdminPagesBase {

  static $properties = array(
    'title' => 'Мета-теги',
    'order' => 310,
    'class' => 'meta'
  );
  
  protected $allowSlavePage = false;
  
  protected $defaultAction = 'json_edit';
  
  public function action_json_edit() {
    return $this->actionJsonFormUpdate(new PageMetaForm($this->page['id']));
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
  
}
