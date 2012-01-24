<?php

class CtrlPageVUserGroup extends CtrlPageUserGroupBase {

  protected function init() {
    parent::init();
    $this->addSubController(new SubPaTagsTreeUserGroup($this));
  }

  protected function getManager() {
    $oF = new Form(new Fields(array(
      array(
        'title' => 'Название',
        'name' => 'title',
        'required' => true
      ),
      array(
        'title' => 'Домен',
        'name' => 'name',
        'type' => 'domain',
        'required' => true
      ),
      array(
        'title' => 'Картинка',
        'name' => 'image',
        'type' => 'imagePreview',
        'required' => true
      ),
      array(
        'title' => 'Описание',
        'name' => 'text',
        'type' => 'wisiwigSimple'
      ),
    )));
    $m = new DbModelManager('userGroup', $oF);
    $m->imageSizes['mdW'] = 180;
    $m->imageSizes['mdH'] = 800;
    return $m;
  }

  public function action_json_new() {
    $this->json['title'] = 'Создание сообщества';
    $m = $this->getManager();
    if ($m->requestCreate()) return;
    return $this->jsonFormAction($m->oForm);
  }
  
  public function action_json_edit() {
    $this->json['title'] = 'Редактирование сообщества';
    $m = $this->getManager();
    if ($m->requestUpdate($this->oReq->rq('id'))) return;
    return $this->jsonFormAction($m->oForm);
  }
  
}
