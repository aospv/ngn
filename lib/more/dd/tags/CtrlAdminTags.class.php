<?php

class CtrlAdminTags extends CtrlAdmin {

  static $properties = array(
    'title' => 'Тэги',
    'onMenu' => true
  );

  public $groupId;

  public $groupName;

  public $strName;

  /**
   * @var DdTagsTagsBase
   */
  private $oTags;

  protected function initParamActionN() {
    if (!isset($this->params[2]))
      return;
    if (is_numeric($this->params[2])) {
      $this->paramActionN = 3;
    } else {
      $this->paramActionN = 4;
    }
  }

  protected function init() {
    if (isset($this->params[2])) {
      // Если указан 4-й параметр, значит меняем номер экшена
      $this->d['groupId'] = $this->groupId = $this->getParam(2);
      $this->oTags = DdTags::getByGroupId($this->groupId);
      $this->groupName = $this->oTags->getGroup()->getName();
      $this->strName = $this->oTags->getGroup()->getStrName();
      $this->d['field'] = O::get('DdFields', $this->strName)->getField($this->groupName);
      $this->d['structure'] = O::get('DbItems', 'dd_structures')->
        getItemByField('name', $this->strName);
      $this->d['path'] = array(
        array(
          'title' => 'Тэги',
          'link' => Tt::getPath(2)
        ),
        array(
          'title' => $this->d['structure']['title'].': '.$this->d['field']['title'],
          'link' => Tt::getPath(4)
        ),
      );
    }
    $this->addSubController(new SubPaTagsTree($this));
  }

  protected function setPathTplData() {
    if ($this->params[3]) {
      $this->d['path'] .= ' / <a href="'.Tt::getPath(4).'">'.
        $this->d['page']['title'].'</a>';
    }
  }

  public function action_default() {
    $r = db()->query(
    '
    SELECT
      tags_groups.*,
      dd_fields.title AS title,
      dd_structures.title AS strTitle
    FROM tags_groups
    LEFT JOIN dd_fields ON tags_groups.name=dd_fields.name
    LEFT JOIN dd_structures ON tags_groups.strName=dd_structures.name
    ');
    $items = array();
    foreach ($r as $v) {
      $items[$v['strName']]['title'] = $v['strTitle'];
      $items[$v['strName']]['items'][$v['id']] = $v;
    }
    $this->d['items'] = $items;
    $this->setPageTitle('Редактирование тэгов');
  }

  public function action_list() {
    $this->setPageTitle("Поле «{$this->d['field']['title']}»");
    if ($this->oTags->getGroup()->isTree()) {
      $this->d['tpl'] = 'tags/listTree';
    } else {
      $this->d['tags'] = $this->oTags->getTags();
      $this->d['tpl'] = 'tags/listFlat';
    }
  }

  public function action_updateStr() {
    if (!$this->fieldName)
      throw new NgnException('$this->fieldName not defined');
    DdTags::updateTagsStr($this->oReq->r['tagsStr'], $this->fieldName, $this->pageId);
    $this->redirect('referer');
  }

  public function action_deleteTag() {
    DdTags::deleteById($this->oReq->r['tagId']);
    $this->redirect('referer');
  }

  public function action_updateCounts() {
    //DdTags::rebuildCounts();
    DdTags::rebuildParents();
    $this->redirect();
  }

  public function action_new() {
    $this->setPageTitle('Создание тэга для поля «<b>'.$this->d['field']['title'].
      '</b>» структуры «<b>'.$this->d['structure']['title'].'</b>»');
  }
  
  public function action_edit() {
    $this->d['tag'] = DbModelCore::get('tags', $this->oReq->rq('id'));
  }
  
  public function action_create() {
    $this->oTags->create(array('title' => $this->oReq->rq('title')));
    $this->redirect(Tt::getPath(3).'/list');
  }

  public function action_update() {
    DbModelCore::update('tags', $this->oReq->rq('tagId'), array('title' => $this->oReq->rq('title')));
  }

  public function action_import() {
    $this->d['tree'] = $this->oTags->getGroup()->isTree();
    $this->d['tpl'] = 'tags/import';
    $this->setPageTitle('Импортирование тэгов поля «'.$this->d['field']['title'].'»');
  }

  public function action_makeImport() {
    if (!empty($this->oReq->r['deleteBeforeImport']))
      $this->oTags->delete();
    if (strstr(get_class($this->oTags), 'Flat'))
      $this->oTags->setImportSeparator($this->oReq->r['sep'] == 'quote' ? "," : "\n");
    $this->oTags->import($this->oReq->rq('text'));
    $this->redirect(Tt::getPath(3) . '/list');
  }

  public function action_json_pageSearch() {
    $this->json['html'] = Tt::getTpl('common/searchResults', 
      array(
        'name' => 'pageId', 
        'items' => Pages::searchPage($this->oReq->rq('mask'))
      ));
  }

  public function action_ajax_reorder() {
    foreach ($this->oReq->rq('items') as $item)
      $ids[] = preg_replace('/item_(\d*)_(\d*)/', '$1', $item);
    DbShift::items($ids, 'tags');
  }

  public function action_ajax_rename() {
    DbModelCore::update('tags', $this->oReq->rq('id'), array('title' => $this->oReq->rq('title')));
  }

  public function action_ajax_delete() {
    DdTags::deleteById($this->oReq->rq('id'));
  }

  public function action_ajax_deleteGroup() {
    DdTagsGroup::getObjById($this->oReq->rq('id'))->delete();
  }

  public function action_json_create() {
    $id = $this->oTags->create(array(
      'title' => $this->oReq->rq('title'),
      'parentId' => $this->oReq->rq('parentId'))
    );
    $this->json = DbModelCore::get('tags', $id)->r;
  }

  public function action_json_getTree() {
    $this->json = O::get('MifTree')->setData($this->oTags->getTree())->getTree();
  }

}