<?php

class DdCore {

 static public function table($strName) {
   return 'dd_i_'.$strName;
 }
 
 static public function filesDir($strName) {
   return 'dd/'.$strName;
 }
 
 static public function isStaticController($name) {
   return PageControllersCore::hasAncestor($name, 'ddStatic');
 }
 
 static public function isDdController($name) {
   return PageControllersCore::hasAncestor($name, 'dd');
 }
 
 static public function isItemsController($name) {
   return PageControllersCore::hasAncestor($name, 'ddItems');
 }
 
 /**
  * @return DdItemsManager
  */
 static public function getItemsManager($pageId, array $options = array()) {
    if (($page = DbModelCore::get('pages', $pageId)) === false) {
      throw new NgnException("No page by id=$pageId");
    }
    if ($page['slave']) {
      $masterPageId = $page['parentId'];
      $masterStrName = DbModelCore::get('pages', $masterPageId)->r['strName'];
      $oItems = new DdSlaveItems(
        $page['id'],
        $masterStrName,
        $masterPageId
      );
      $oForm = new DdSlaveForm(
        new DdFields($page['strName']),
        $page['id'],
        $masterStrName,
        $masterPageId
      );
    } else {
      $oItems = new DdItems($page['id']);
      $oForm = new DdForm(
        new DdFields($page['strName']),
        $page['id']
      );
    }
    if (($path = Hook::getPath('dd/initItemsManager')) !== false) include $path;
    $oIM = new DdItemsManager($oItems, $oForm, $options);
    $oIM->defaultActive = (!empty($page['settings']['premoder'])) ? 0 : 1;
    if (isset($page['settings']['order']) and $page['settings']['order'] == 'oid') {
      $oIM->setOidAddMode(true);
    }
    if (DdCore::isStaticController($page['controller'])) {
      Arr::checkEmpty($options, 'staticId');
      $oIM->setStaticId($options['staticId']);
    }
    return $oIM;
  }
  
  const masterFieldName = 'mstr';
  
  static public function getSlaveStrName($masterStrName) {
    return $masterStrName.'Slave';
  }
  
  static public function getMasterStrName($slaveStrName) {
    return Misc::removeSuffix('Slave', $slaveStrName);
  }
  
  static public function extendItemsData(array $items) {
    foreach ($items as &$v)
      $v['item'] = O::get('DdItems', $v['pageId'])->getItemF($v['itemId']);
    return $items;
  }

}
