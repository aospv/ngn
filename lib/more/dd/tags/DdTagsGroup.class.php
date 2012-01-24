<?php

class DdTagsGroup {

  /**
   * Уникальный идентификатор группы
   *
   * @var integer
   */
  public $id;
  
  /**
   * Имя структуры группы
   *
   * @var string
   */
  public $strName;

  /**
   * Имя группы тэгов
   *
   * @var string
   */
  public $name;

  /**
   * Название группы (поля) 
   *
   * @var string
   */
  protected $title;
  
  /**
   * Флаг определяет то, что тэги этой группы управляются записыми, 
   * т.е. могут создаваться при создании записи с несуществующим тэгом 
   *
   * @var bool
   */
  public $itemsDirected;

  /**
   * Флаг определяет то, что имена тэгов этой группы должны быть уникальны
   *
   * @var bool
   */
  public $unicalTagsName;

  /**
   * Флаг определяет то, что тэги этой группы могут иметь древовидную структуру
   *
   * @var bool
   */
  protected $tree;
  
  /**
   * Тип поля, которому принадлежат теги
   *
   * @var string
   */
  protected $fieldType;
  
  /**
   * Можно ли выбирать один тег или несколько
   *
   * @var bool
   */
  protected $multi;
  
  const ERR_GROUP_NOT_EXISTS = 71;
  
  public function __construct($strName, $name) {
    if (!$r = $this->get($strName, $name))
      throw new NgnException("Group (strName=$strName, name=$name) does not exists",
        self::ERR_GROUP_NOT_EXISTS);
    $this->strName = $r['strName'];
    foreach ($this->get($strName, $name) as $k => $v) {
      $this->$k = $v;
    }
    $this->tree = DdTags::isTagTreeType($this->fieldType);
    if (empty($this->fieldType))
      throw new NgnException('Field for tag "'.$name.'" of "'.$strName.'" structure does not exists');
    $this->multi = strstr(strtolower($this->fieldType), 'multi');
  }
  
  public function getStrName() {
    return $this->strName;
  }
  
  public function getName() {
    return $this->name;
  }
  
  public function getId() {
    return $this->id;
  }
  
  public function isItemsDirected() {
    return $this->itemsDirected;
  }
  
  public function isUnicalTagsName() {
    return $this->unicalTagsName;
  }
  
  public function isTree() {
    return $this->tree;
  }
  
  public function isMulti() {
    return $this->multi;
  }
  
  public function getFieldType() {
    return $this->fieldType;
  }
  
  /**
   * Returns Tags Group data
   *
   * @param   string  Structure data
   * @param   string  Tags Group name
   * @return  array
   */
  static public function get($strName, $name) {
    return db()->selectRow('
    SELECT
      tags_groups.*,
      dd_fields.title,
      dd_fields.name AS fieldName,
      dd_fields.type AS fieldType
    FROM tags_groups
    LEFT JOIN dd_fields ON dd_fields.name=tags_groups.name AND
                           dd_fields.strName=tags_groups.strName
    WHERE
      tags_groups.strName=? AND
      tags_groups.name=?',
      $strName, $name);
  }
  
  /**
   * Returns Tags Group data
   *
   * @param   string  Structure data
   * @param   string  Tags Group name
   * @return  array
   */
  static public function getById($id) {
    return db()->selectRow('
    SELECT
      tags_groups.*,
      dd_fields.title
    FROM tags_groups
    LEFT JOIN dd_fields ON dd_fields.name=tags_groups.name
    WHERE
      tags_groups.id=?d', $id);
  }
  
  static public function getObjById($id) {
    $r = db()->selectRow('SELECT strName, name FROM tags_groups WHERE id=?d', $id);
    //die2($r);
    return new DdTagsGroup($r['strName'], $r['name']);
  }
  
  /**
   * Создает группу
   *
   * @param   string  Имя группы
   * @param   integer ID раздела
   * @param   bool    Флаг определяющий, управляются ли тэги этой группы тэг-записями
   * @param   bool    Флаг определяющий, уникально ли имя тэгов
   * @param   bool    Флаг определяющий, могут ли быть тэги древовидными
   */
  static public function create($strName, $name, $itemsDirected = true, $unicalTagsName = true, 
  $tree = false) {
    if (!$name) throw new NgnException('$name not defined');
    db()->query(
      'REPLACE INTO tags_groups
       SET strName=?, name=?, itemsDirected=?d, unicalTagsName=?d, tree=?d', 
      $strName, $name, $itemsDirected, $unicalTagsName, $tree);
    //return new TagsGroup($strName, $name);
  }
  
  /**
   * Переименовывает группы
   *
   * @param   integer   Структура группы
   * @param   string    Текущее имя группы
   * @param   string    Новое имя группы
   * @param   bool      Флаг определяющий, управляются ли тэги этой группы тэг-записями
   * @param   bool      Флаг определяющий, уникально ли имя тэгов
   * @param   bool      Флаг определяющий, могут ли быть тэги древовидными
   */
  static public function update($strName, $name, $newName, $itemsDirected = true, $unicalTagsName = true, 
    $tree = false) {
    if (!self::get($strName, $name)) {
      throw new NgnException("Tags group strName=$strName, name=$name does not exists.");
    }
    // -----------------------------------------
    db()->query('
      UPDATE tags_groups
      SET name=?, itemsDirected=?d, unicalTagsName=?d, tree=?d
      WHERE strName=? AND name=?',
      $newName, $itemsDirected, $unicalTagsName, $tree, $strName, $name);
    db()->query('UPDATE tags SET groupName=? WHERE groupName=? AND strName=?',
      $newName, $name, $strName);
    db()->query('UPDATE tags_items SET groupName=? WHERE groupName=? AND strName=?',
      $newName, $name, $strName);
  }

  public function delete() {
    db()->query('DELETE FROM tags_groups WHERE id=?d', $this->id);
    db()->query('DELETE FROM tags_items WHERE strName=? AND groupName=?', 
      $this->strName, $this->name);
    db()->query('DELETE FROM tags WHERE strName=? AND groupName=?',
      $this->strName, $this->name);
    // ------------------------x---------------------------
    O::delete(get_class($this), $this->name);
  }

}