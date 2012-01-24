<?php

class CtrlAdminConfigManager extends CtrlAdmin {
  
  static $properties = array(
    'title' => 'Конфигурация',
    'descr' => 'Управление конфигурационными файлами',
    'onMenu' => true,
    'order' => 80
  );
  
  protected $defaultConstantName = 'more';
  protected $defaultVarName = 'admins';
  protected $defaultStructType = 'array';
  protected $configType;
  protected $configName;
  
  protected function init() {
    if (! isset($this->params[2]))
      $this->params[2] = 'constants';
    $this->d['configType'] = 
      $this->configType = $this->params[2] == 'vvv' ? 'vvv' : 'constants';
    
    if ($this->configType == 'vvv') $this->configType = 'vars'; 
    // ---------------------------------------------------    
    if ($this->configType == 'vars')
      $this->d['configName'] = 
        $this->configName = isset($this->params[3]) ? $this->params[3] : $this->defaultVarName;
    else
      $this->d['configName'] =
        $this->configName = isset($this->params[3]) ? $this->params[3] : $this->defaultConstantName;
      // ---------------------------------------------------    
    $this->d['sections'] = SiteConfig::getTitles($this->configType);
    $this->d['canUpdate'] = SiteConfig::hasSiteVar($this->configName);
  }

  public function action_default() {
    $oF = ConfigManagerFormFactory::get($this->configType, $this->configName);
    if ($oF->update()) {
      $this->redirect();
      return;
    }
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'configManager/default';
  }
  
  public function action_ajax_deleteValue() {
    if ($this->configType == 'constants')
      throw new NgnException('Deleting of constants not allowed');
    $oF = ConfigManagerFormFactory::get($this->configType, $this->configName);
    $vars = Config::getVar($this->configName);
    $key = $this->oReq->r['name'];
    $key = substr($key, 1, strlen($key));
    eval('unset($vars'.$key.');');
    if ($oF->getType() == 'array') $vars = array_values($vars);
    SiteConfig::updateVar($this->configName, $oF->formatForUpdate($vars));
  }

  public function action_deleteSiteConfig() {
    if ($this->configType == 'constants')
      throw new NgnException('You can not delete constants sections');
    SiteConfig::deleteVarSection($this->configName);
    $this->redirect();
  }
  
}
