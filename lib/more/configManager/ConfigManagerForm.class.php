<?php

class ConfigManagerForm extends Form {
  
  protected $configType;
  protected $configName;
  protected $configValues;
  protected $configStruct;
  protected $configFields;
  protected $configDefaultData;
  protected $alert;
  
  /**
   * @param string  Тип конфигурации (vars/constants)
   * @param string  Имя конфигурационной группы (admins/database/emails/...) 
   */
  public function __construct($type, $name) {
    $type = str_replace('vvv', 'vars', $type);
    $this->configType = $type;
    $this->configName = $name;
    if ($this->configType == 'vars')
      $this->configValues = Config::getVar($this->configName, true);
    else
      $this->configValues = SiteConfig::getConstants($this->configName);
    if (!$this->configValues) $this->configValues = array();
    $this->addEmptyNewValues();
    // ------------------------
    $this->init();
    $this->initStruct();
    parent::__construct(new Fields(Fields::keyAsName($this->configStruct['fields'])));
    $this->setElementsData($this->configValues);
    if (!empty($this->configStruct['visibilityConditions'])) {
      foreach ($this->configStruct['visibilityConditions'] as $cond) {
        $this->addVisibilityCondition(
          $cond['headerName'],
          $cond['condFieldName'],
          $cond['cond']
        );
      }
    }
  }
  
  protected function structExists() {
    return !empty($this->configStruct) ? true : false;
  }

  protected function addEmptyNewValues() {
  	return;
    if ($this->isMultidimension()) {
      // Создаем пустые значения для нового поля. 2 уровня
      $this->configValues['_new'] = array('');
    } else {
      // 1 уровень
      $this->configValues['_new'] = '';
    }
  }
  
  protected function initStruct() {
    // Приведение типов. Т.е. пустой меняем на 'text'
    $structs = SiteConfig::getStruct($this->configType);
    if (!isset($structs[$this->configName]))
      throw new NgnException('Structure "'.$this->configName.'" not exists');
      
    $struct = $structs[$this->configName];
    if (!$struct['fields']) return;
    foreach ($struct['fields'] as &$vv) {
      if (!isset($vv['type']))
        $vv['type'] = 'text';
        // Для типа "fieldSet" перебераем вложенные поля
      if ($vv['type'] == 'fieldSet') {
        foreach ($vv['fields'] as &$vvv) {
          if (!isset($vvv['type']))
            $vvv['type'] = 'text';
        }
      }
    }
    if (!empty($this->alert)) {
      $struct['fields'] = array_merge(
        array('alert' => array(
          'title' => $this->alert,
          'type' => 'header'
        )),
        $struct['fields']
      );
    }
    $this->configStruct = $struct;
  }

  protected function _update(array $data) {
    if ($this->configType == 'vars') {
      SiteConfig::updateVar($this->configName, $data);
    } else {
      SiteConfig::replaceConstants($this->configName, $data);
    }
    $this->afterUpdate($data);
  }

  protected function afterUpdate(array $values) {
  }
  
  // ------------------------------------------------------------------------------

  protected function init() {
    if ($this->configType == 'constants') {
      $this->alert = 'Внимание! Изменение этих параметров может повлиять на работоспособность сайта';
    }
  }
  
}
