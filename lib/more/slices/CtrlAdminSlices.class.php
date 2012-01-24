<?php

class CtrlAdminSlices extends CtrlAdminPagesBase {

  static $properties = array(
    'title' => 'Слайсы',
    'onMenu' => true,
    'order' => 5
  );
  
  protected function initAction() {
    if (isset($this->params[2])) $this->defaultAction = 'edit';
    parent::initAction();
  }
  
  public function action_default() {
    $this->setPageTitle('Все слайсы');
    $this->d['items'] = db()->query('
    SELECT
      slices.*,
      p.title AS pageTitle,
      p.path AS pagePath,
      p2.title AS pageTitle2,
      p2.path AS pagePath2
    FROM slices
    LEFT JOIN pages AS p ON slices.pageId=p.id
    LEFT JOIN pages AS p2 ON p.parentId=p2.id
    ');
  }
  
  public function action_new() {
    $oF = new Form(new Fields(array(
      array(
        'title' => 'Название',
        'name' => 'title',
        'required' => true
      ),
      array(
        'title' => 'ID',
        'name' => 'id',
        'type' => 'name',
        'required' => true
      ),
      array(
        'title' => 'Текст',
        'name' => 'text',
        'type' => 'wisiwig'
      )
    )));
    if ($this->pageId) {
      $this->setPageTitle('Создание слайса для раздела «'.$this->d['page']['title'].'»');
    } else {
      $this->setPageTitle('Создание глобального слайса');
    }
    if ($oF->isSubmittedAndValid()) {
      $data['pageId'] = $this->pageId;
      Slice::replace($data);
      //$slice = Slice::getByName($_POST['name'], $pageId);
      $this->redirect(Tt::getPath(2).'/'.$data['id']);
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'slices/new';
  }
  
  public function action_edit() {
    if (!($slice = DbModelCore::get('slices', $this->params[3])))
      throw new NgnException("Slice id={$this->params[3]} does not exists");
    $this->d['slice'] = $slice;
    $this->setPagesPath($slice['pageId']);
    $this->setPageTitle('Редактирование: <b>'.$slice['title'].'</b>'.' раздела «'.$this->d['page']['title'].'»');
    $this->d['attachId'] = $this->params[3];
    $this->d['tpl'] = 'slices/edit';
  }
  
  public function action_update() {
    Slice::replace(array_merge(array('id' => $this->getParam(3)), $_POST));
    $this->redirect();
  }
  
  public function action_delete() {
    DbModelCore::delete('slices', $this->getParam(3));
    $this->redirect(Tt::getPath(2));
  }
  
}
