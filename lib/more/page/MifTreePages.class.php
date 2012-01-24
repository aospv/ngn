<?php

class MifTreePages extends MifTree {
  
  public $childrenKey = 'children';
  
  public function __construct() {
    $this->allowedDataParams = array_merge($this->allowedDataParams,
      array('folder', 'title', 'path', 'controller'));
    $this->initData();
  }
  
  protected function initData() {
    $this->setData(array(DbModelPages::getTree()->getTree()));
  }
  
  protected function setNodeType(array &$node, array $data) {
    $node['type'] = !empty($data['folder']) ? 'folder' : 'page';
  }
  
  protected function setNodeCls(array &$node, array $data) {
    $node['property']['cls'] = trim(Misc::cleanupSpaces(implode(' ',array(
      !empty($data['onMenu']) ? '' : 'offMenu',
      !empty($data['active']) ? '' : 'nonActive',
      !empty($data['home']) ? 'home' : '',
      !empty($data['module']) ? 'mif-pm-'.$data['module'] : ''
    ))));
    $node['data']['editableContent'] = !empty($data['settings']['strName']);
    if (!empty($data['controller']))
      $node['data']['dd'] = DdCore::isDdController($data['controller']);
    if (!empty($data['module'])) {
      $node['data']['canLinked'] = $data['module'] != 'link';
      $node['data']['path'] = (string)$data['path'];
      //PageModuleCore::hasStaticLayout($data['module']);
    }
  }
  
}