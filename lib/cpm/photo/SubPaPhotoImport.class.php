<?php

class SubPaPhotoImport extends SubPa {

  protected $pageId;

  public function __construct(CtrlCommon $oPA, $pageId) {
    parent::__construct($oPA);
    $this->pageId = $pageId;
    O::get('PagePriv', DbModelCore::get('pages', $this->pageId), Auth::get('id'))->check('create');
  }

  public function action_json_fancyUpload() {
    $oIM = DdCore::getItemsManager($this->pageId);
    $oIM->disableFUdelete = true;
    $oUT = O::get('FancyUploadTemp', array(
      'multiple' => true
    ));
    foreach ($oUT->getFiles() as $values) {
      foreach ($values['name'] as $i => $name) {
        //try {
          $oIM->create(array_merge(array(
            'title' => (bool)$this->oReq->reqAnyway('filenameAsTitle') ? File::stripExt($name) : '',
            'image' => array(
              'tmp_name' => $values['tmp_name'][$i],
            )
          ), $this->oReq->r));
        //} catch (Exception $e) {}
      }
    }
    $this->oPA->json['success'] = true;
    $oUT->delete();
  }
  
  public function action_json_upload() {
    $this->oPA->json['title'] = 'Добавление нескольких изображений';
    $oF = new Form(new Fields(array(
      array(
        'title' => 'Использовать имена файлов в качестве названий',
        'name' => 'filenameAsTitle',
        'type' => 'boolCheckbox'
      ),
      array(
        'title' => 'Изображения',
        'name' => 'images',
        'type' => 'fieldList',
        'fieldsType' => 'image',
        'addTitle' => 'Добавить изображение'
      )
    )), array(
      'submitTitle' => 'Добавить'
    ));
    $oF->setElementsData();
    if ($oF->isSubmitted()) {
      $oIM = DdCore::getItemsManager($this->pageId);
      $data = Arr::filter_empties2($oF->getData());
      if (isset($data['images'])) {
        foreach ($data['images'] as $v) {
          $oIM->create(array(
            'title' => $data['filenameAsTitle'] ? File::stripExt($v['name']) : '',
            'image' => $v
          ) + $this->oPA->getItemParams());
        }
      }
      return;
    }
    return $oF;
  }

}