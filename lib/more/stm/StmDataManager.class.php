<?php

class StmDataManager extends DataManagerAbstract {

  /**
   * type - для имен классов
   * subType - для папок
   */
  protected $requiredOptions = array('type', 'subType', 'location');
  
  public function __construct(array $options = array()) {
    $this->setOptions($options);
    parent::__construct(new Form(
      new Fields($this->convertFields()),
      array('filterEmpties' => true)
    ));
  }
  
  /**
   * @return StmData
   */
  public function getStmData(array $options = array()) {
    // здесь нужен ID
    return O::get(
      'Stm'.ucfirst($this->options['type']).'Data',
      O::get(
        'StmDataSource',
        $this->options['location']
      ),
      array_merge($this->options, $options)
    );
  }
  
  protected function convertFields() {
    $fields = array();
    $names = array();
    foreach ($this->getStmData()->getStructure()->str['fields'] as $v) {
      if (!isset($v['name']) and isset($v['s'])) {
        if (empty($v['p'])) throw new EmptyException('$v[p]');
        $v['name'] = $this->getFieldNameByCssData($v);
        if (in_array($v['name'], $names))
          throw new NgnException("{$v['name']} alredy in use. v: ".getPrr($v));
        $names[] = $v['name'];
      }
      $fields[] = $v;
    }
    return $fields;
  }
  
  protected function convertCssDataToFormFormat(array $data) {
    $r = array();
    foreach ($data as $v) {
      if (!is_array($v) or !isset($v['s'])) continue;
      $r[$this->getFieldNameByCssData($v)] =
        str_replace(' !important', '', $v['v']);
    }
    return $r;
  }
  
  const PARAMS_DELIMITER = ' & ';
  
  protected function getFieldNameByCssData(array $v) {
    if (!empty($v['pGroup'])) {
      $pp = $v['pGroup'];
    } else {
      // Если параметр групповой, берём для имени только первый
      $params = explode(self::PARAMS_DELIMITER, $v['p']);
      $pp = $params[0];
    }
    return Misc::parseId($v['s']).'_'.Misc::parseId($pp);
  }
  
  protected function getItem($id) {
    return $this->getStmData(array('id' => $id))->data;
  }
  
  protected function beforeCreate() {
    $this->data = array_merge(array(
      'siteSet' => $this->options['siteSet'],
      'design' => $this->options['design']
    ), $this->data);
  }
  
  protected function _create() {
    return $this->getStmData(array_merge($this->options, array('new' => true)))->
      setData($this->data)->save()->id;
  }
  
  protected function beforeUpdate() {
    $o = $this->getStmData(array('id' => $this->id));
    $this->data = array_merge(array(
      'siteSet' => $o->data['siteSet'],
      'design' => $o->data['design']
    ), $this->data);
  }
  
  protected function _update() {
    $this->getStmData(array('id' => $this->id))->setData($this->data)->save();
  }
  
  protected function _delete() {
    $this->getStmData(array('id' => $this->id))->delete();
  }
  
  protected function source2formFormat() {
    $this->defaultData = array_merge(
      $this->convertCssDataToFormFormat(empty($this->defaultData['cssData']) ?
        array() : $this->defaultData['cssData']
      ),
      empty($this->defaultData['data']) ? array() : $this->defaultData['data']
    );
  }
  
  protected function form2sourceFormat() {
    $r = array();
    foreach ($this->data as $name => $v) {
      if (empty($this->oForm->getElement($name)->options['s'])) {
        $r['data'][$name] = $v;
      } else {
        $el = $this->oForm->getElement($name);
        $params = explode(self::PARAMS_DELIMITER, $el->options['p']);
        if (count($params) > 1) {
          $pGroup = $params[0];
          foreach ($params as $p) {
            $r['cssData'][] = array(
              's' => $el->options['s'],
              'p' => $p,
              'pGroup' => $pGroup,
              'v' => $v.(!empty($el->options['important']) ? ' !important' : '')
            );
          }
        } else {
          $r['cssData'][] = array(
            's' => $el->options['s'],
            'p' => $params[0],
            'v' => $v.(!empty($el->options['important']) ? ' !important' : '')
          );
        }
      }
    }
    $this->data = $r;
  }
  
  public function updateField($id, $fieldName, $value) {
    if ($this->oForm->oFields->isFileType($fieldName)) $value = basename($value); // подстановка путей происходит динамически
    $o = $this->getStmData(array('id' => $id));
    $o->data['data'][$fieldName] = $value;
    $o->save();
  }
  
  public function setDataValue($bracketName, $value) {
    BracketName::setValue($this->data['data'], $bracketName, $value);
  }
  
  public function updateFileCurrent($file, $fieldName) {
    $attachePath = $this->getAttachePath();
    $filename = $this->getAttacheFilename($fieldName);
    Dir::make($attachePath);
    copy($file, $attachePath.'/'.$filename);
    $this->updateField($this->options['id'], $fieldName, $filename);
  }
  
  public function requestUpdateCurrent() {
    return $this->requestUpdate($this->options['id']);
  }
  
  public function getAttacheFolder() {
    return $this->getStmData()->getThemeWpath().'/'.StmCss::FOLDER_NAME.'/'.
      $this->getStmData()->getName();
  }
  
  public function getAttachePath() {
    return $this->getStmData()->getThemePath().'/'.StmCss::FOLDER_NAME.'/'.
      $this->getStmData()->getName();
  }
  
  protected function afterUpdate() {
    SFLM::clearJsCssCache();
  }
  
}
