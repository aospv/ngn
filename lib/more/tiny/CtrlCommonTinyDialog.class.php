<?php

abstract class CtrlCommonTinyDialog extends CtrlCommon {

  protected $useParentPlugin = false;

  protected function setDefaultTpl() {
    $this->d['mainTpl'] = 'tiny/popup/main';
  }
  
  protected function init() {
    $this->d['name'] = lcfirst(Misc::removePrefix('CtrlCommonTiny', get_class($this)));
    $this->d['pluginName'] = strtolower(Misc::removePrefix(
      'CtrlCommonTiny',
      $this->useParentPlugin ?
        get_parent_class($this) : get_class($this)
    ));
    $this->d['base'] = 'http://'.SITE_DOMAIN.O::get('Req')->getBase().'/';
  }
  
  protected function extendTplData() {
    if (!isset($this->d['tpl']))
      $this->d['tpl'] = 'tiny/popup/'.$this->d['name'].'/'.$this->action;
    if (!isset($this->d['js']) and
    file_exists(STATIC_PATH.'/js/tiny_mce/plugins/'.$this->d['pluginName'].'/js/default.js'))
      $this->d['js'] =
        $this->d['base'].'i/js/tiny_mce/plugins/'.$this->d['pluginName'].'/js/default.js';
  }
  
}
