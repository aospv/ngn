<?php

class DdImporter {
  
  /**
   * @var DdItemsManager
   */
  protected $oIM;
  
  /**
   * @var DdImportDataReceiver
   */
  protected $oReceiver;
  
  public function __construct(DdItemsManager $oIM, DdImportDataReceiver $oReceiver) {
    $this->oIM = $oIM;
    $this->oReceiver = $oReceiver;
  }
  
  public function makeImport() {
    $oF = $this->oReceiver->getFieldObj();
    $fieldTypes = $oF->getTypes();    
    foreach ($this->oReceiver->getData() as $v) {
      $method = 'f_'.$fieldType;
      $formatterExists = method_exists($this, $method);
      $_v = $v;
      foreach (array_keys($v) as $fieldName) {
        $value = trim($v[$fieldName]);
        if (empty($value)) continue;
        $fieldType = $fieldTypes[$fieldName];
        $method = 'f_'.$fieldType;
        // Если есть форматтер для этого типа, очищаем поле
        if (method_exists($this, $method))
          $v[$fieldName] = '';
      }      
      $itemId = $this->oIM->create($v);
      foreach (array_keys($_v) as $fieldName) {
        $value = trim($_v[$fieldName]);
        if (empty($value)) continue;
        $fieldType = $fieldTypes[$fieldName];
        $method = 'f_'.$fieldType;
        if (method_exists($this, $method))
          $this->$method($value, $fieldName, $itemId);
      }
    }
    die2('Import complete');
  }
  
  protected function tagsTreeSelectTagsCreate($value, $fieldName) {
    $oTags = DdTags::get($this->oIM->oItems->strName, $fieldName);
    $tags = array_map('trim', explode('→', $value));
    $parentId = 0;
    foreach ($tags as $tag) {
      $parentId = $oTags->create($tag, $parentId);
      $tagIds[] = $parentId;
    }
    return $tagIds;
  }
  
  protected function f_tagsTreeSelect($value, $fieldName, $itemId) {
    $tagIds = $this->tagsTreeSelectTagsCreate($value, $fieldName);
    DdTagsItems::createByIds(
      $this->oIM->oItems->strName,
      $fieldName,
      $itemId,
      $tagIds
    );
    $this->oIM->oItems->update($itemId, array(
      $fieldName => $tagIds[count($tagIds)-1]
    ));
  }
  
  protected function tagsTreeMultiselectTagsCreate($value, $fieldName) {
    $oTags = DdTags::get($this->oIM->oItems->strName, $fieldName);
    $value = array_map('trim', explode(";", $value));
    foreach ($value as $n => $tags) {
      $tags = array_map('trim', explode('→', $tags));
      $parentId = 0;
      foreach ($tags as $tag) {
        $parentId = $oTags->create($tag, $parentId);
        $collections[$n][] = $parentId;
      }
    }
    return $collections;
  }
  
  protected function f_tagsTreeMultiselect($value, $fieldName, $itemId) {
    $collectionTagIds = $this->tagsTreeMultiselectTagsCreate($value, $fieldName);
    DdTagsItems::createByIdsCollection(
      $this->oIM->oItems->strName,
      $fieldName,
      $itemId,
      $collectionTagIds
    );
    $tagIds = array();
    foreach ($collectionTagIds as $_tagIds)
      $tagIds = Arr::append($tagIds, $_tagIds);
    $this->oIM->oItems->update($itemId, array(
      $fieldName => serialize($tagIds)
    ));
  }
  
  protected function f_tagsSelect($value, $fieldName, $itemId) {
    DdTagsItems::createByIds(
      $this->oIM->oItems->strName,
      $fieldName,
      $itemId,
      array(DdTags::get($this->oIM->oItems->strName, $fieldName)->create($value))
    );
  }
  
  protected function f_tagsMultiselect() {
  }
  
  // ----------------------------------
  
  static public function import($pageId, $file) {
    $strName = db()->selectCell('SELECT strName FROM pages WHERE id=?d', $pageId);
    $oI = new DdItems($pageId);
    $oI->forceDublicateInsertCheck = true;
    $o = new DdImporter(
      new DdItemsManager(
        $oI,
        new DdForm(new DdFields($strName), $pageId)
      ),
      new DdImportDataExcel(
        new DdImportField($strName),
        $file
      )
    );
    $o->makeImport();
  }
  
}
