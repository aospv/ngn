<?php

class CtrlAdminPageBlocks extends CtrlAdminPagesBase {

  static $properties = array(
    'title' => 'Блоки',
    'onMenu' => true,
    'order' => 320
  );
  
  protected $blockColN;
  protected $blockType;
  protected $globalBlocksAdded;
  
  protected function init() {
  	parent::init();    
    $this->d['globalBlocksAdded'] = 
      PageBlockCore::globalBlocksDuplicatesExists($this->pageId);
  }
  
  public function action_default() {
    if ($this->pageId) {
      $this->setPageTitle('Блоки раздела «<b>'.$this->page['title'].'</b>»');
    } else {
      $this->setPageTitle('Блоки по умолчанию');
    }
    $cols = PageLayout::getColsByLayout($this->pageId);
    $this->d['colsNumber'] = count($cols);
    $this->d['blocks'] = PageBlockCore::sortBlocks(
      PageBlockCore::getDynamicBlocks($this->pageId),
      $this->d['colsNumber']
    );
    $this->d['cols'] = PageLayout::getColsByLayout($this->pageId);
  }
  
  protected function jsonCreateBlockTitle() {
    $this->json['title'] = isset($this->page) ?
      'Создание блока раздела «'.$this->page['title'].'»' :
      'Создание глобального блока';
  }
  
  /**
   * Создание блока. Шаг 1. Выбор типа
   */
  public function action_json_newBlock() {
    $this->jsonCreateBlockTitle();
    $oF = new PageBlockTypeForm(array('submitTitle' => 'Продолжить создание блока'));
    if ($oF->isSubmittedAndValid()) {
      $this->json['nextFormUrl'] =
        Tt::getPath(3).'/json_newBlockStep2/'.$this->getParam(4).'/'.$oF->elementsData['type'];
      return;
    }
    return $oF;
  }
  
  /**
   * Шаг 2. Выполняется только при наличии для этого типа pre-полей
   */
  public function action_json_newBlockStep2() {
    $this->jsonCreateBlockTitle();
    $oPBS = PageBlockCore::getStructure(
      $this->getParam(5),
      array('pageId' => $this->pageId)
    );
    $preFields = $oPBS->getPreFields();
    if ($preFields) {
      $oF = new Form(new Fields($preFields), array(
        'submitTitle' => 'Продолжить создание блока'));
      if ($oF->isSubmittedAndValid()) {
        $this->json['nextFormUrl'] = Tt::getPath(3).'/json_newBlockStep3/'.
          $this->getParam(4).'/'.$this->getParam(5).'?'.http_build_query($oF->getData());
        return;
      }
      return $oF;
    } else {
      $oF = new Form(new Fields(array(array(
        'type' => 'header',
        'title' => '<br />Продолжите...<br /><br />'
      ))), array(
        'submitTitle' => 'Продолжить создание блока'));
      if ($oF->isSubmitted()) {
        $query = (($hiddenParams = $oPBS->getHiddenParams()) != null) ?
          '?'.http_build_query($hiddenParams) : '';
        $this->json['nextFormUrl'] = Tt::getPath(3).'/json_newBlockStep3/'.
          $this->getParam(4).'/'.$this->getParam(5).$query;
        return;
      }
      return $oF;
    }
  }
  
  /**
   * Финальный шаг. Создание блока
   */
  public function action_json_newBlockStep3() {
    $this->jsonCreateBlockTitle();
    $oPBS = PageBlockCore::getStructure($this->getParam(5));
    if (!empty($_GET)) $oPBS->setPreParams($_GET);
    $createData = array(
      'ownPageId' => $this->pageId,
      'type' => $this->getParam(5),
      'colN' => $this->getParam(4),
      'global' => empty($this->pageId)
    );
    $oMM = new PageBlockModelManager($oPBS, $createData);
    if ($oMM->requestCreate()) return;
    return $oMM->oForm;
  }
  
  public function action_ajax_deleteBlock() {
    if (!Misc::isGod()) throw new NgnException('Access denied');
    PageBlockCore::delete($this->oReq->rq('blockId'));
  }
  
  public function action_ajax_updateBlocks() {
  	//die2($this->oReq->rq('cols'));
    //foreach ($this->oReq->rq('cols') as $col) {
    foreach ($this->oReq->rq('cols') as $cols) {
      foreach ($cols as $v) {
        PageBlockCore::updateColN($v['id'], $v['colN'], $this->page['id']);
        //prr($v);
        $ids[] = $v['id'];
      }
    }
    //die2($ids);
    DbShift::items($ids, 'pageBlocks');
  }
  
  public function action_json_editBlock() {
    $id = $this->getParam(4);
    $oPBM = DbModelCore::get('pageBlocks', $id);
    $oPBS = PageBlockCore::getStructure($oPBM['type']);
    $oPBS->setPreParamsBySettings($oPBM['settings']);
    $oMM = new PageBlockModelManager($oPBS);
    if ($oMM->requestUpdate($id)) return;
    $this->json['title'] = 'Редактирование блока «'.PageBlockCore::getTitle($oPBM['type']).'»';
    return $oMM->oForm;
  }
  
  public function action_ajax_getBlock() {
    $r = PageBlockCore::getBlockHtmlData(
      DbModelCore::get('pageBlocks', $this->getParam(4)));
    $this->ajaxOutput = $r['html'];
  }
  
  public function action_createGlobalBlocksDuplicates() {
    PageBlockCore::createGlobalBlocksDuplicates($this->pageId);
    $this->redirect(Tt::getPath(3));
  }
  
  public function action_deleteGlobalBlocksDuplicates() {
    PageBlockCore::deleteDuplicateBlocks($this->pageId);
    $this->redirect(Tt::getPath(3));
  }

}