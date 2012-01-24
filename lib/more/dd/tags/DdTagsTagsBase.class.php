<?php

abstract class DdTagsTagsBase implements DbTreeInterface {

  /**
   * @var DdDdTagsGroup
   */
  protected $oTG;
  
  public function __construct(DdTagsGroup $oTG) {
    $this->oTG = $oTG;
  }
  
  public function create(array $data) {
    $data['strName'] = $this->oTG->getStrName();
    $data['groupName'] = $this->oTG->getName();
    return DbModelCore::create('tags', $data);
  }

  /**
   * @var DbCond
   */
  protected $cond;
  
  public function getCond() {
    if (isset($this->cond)) return $this->cond;
    return $this->cond = DbCond::get()->
      addF('strName', $this->oTG->strName)->
      addF('groupName', $this->oTG->name)->
      setOrder('oid');
  }

  /**
   * @return DdTagsGroup
   */
  public function getGroup() {
    return $this->oTG;
  }
  
  public function getParentId($id) {
    return db()->selectCell('SELECT parentId FROM tags WHERE id=?d', $id);
  }
  
  public function updateTitle($id, $title) {
    db()->query('UPDATE tags SET title=?, name=? WHERE id=?d',
      $title, DdTags::title2name($title), $id);
  }
  
  /**
   * Удаляет все все теги текущей группы
   */
  public function delete() {
    db()->query('DELETE FROM tags WHERE strName=? AND groupName=?',
      $this->oTG->getStrName(), $this->oTG->getName());
    db()->query('DELETE FROM tags_items WHERE strName=? AND groupName=?',
      $this->oTG->getStrName(), $this->oTG->getName());
  }
  
  /**
   * Удаляет определенные в DbCond теги текущей группы
   */
  public function delete2() {
    if (!($ids = db()->ids('tags', $this->getCond()))) return;
    db()->query('DELETE FROM tags WHERE id IN (?a)', $ids);
    db()->query('DELETE FROM tags_items WHERE tagId IN (?a)', $ids);
  }
  
  abstract public function import($text); 
  
  public function notEmpty() {
    $this->getCond()->addFromFilter('cnt', 1);
    return $this;
  }
  
  abstract public function getData(); 
  
}
