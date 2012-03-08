<?php

class StmCore {

  // ------------------ template functions ------------------

  static public function menu(
  $pageName = 'main',
  $linkDddd = '`<a href="`.$link.`"><span>`.$title.`</span></a><i></i><div class="clear"></div>`'
  ) {
    $data = self::getCurrentMenuData();
    return Menu::ul($pageName, $data ? $data->data['data']['levels'] : 1, $linkDddd);
  }
  
  static function slices() {
    if (($data = self::getCurrentThemeData()) === false) return '';
    if (($slices = $data->data['data']['slices']) == null) return;
    foreach ($slices as $v)
      $html .= Slice::html($v['id'], $v['title'], array(
        'absolute' => true,
        'allowAdmin' => $v['allowAdmin']
      ));
    return $html;
  }
  
  // --------------------------------------------------------
  
  static public function getCurrentThemeParams() {
    $r = Config::getVarVar('theme', 'theme');
    if (empty($r['theme']) or $r['theme'] == ':') return false;
    return explode(':', $r);
  }
  
  static public function enabled() {
    return Config::getVarVar('theme', 'enabled', true);
  }
  
  /**
   * Возвращает объект данных для текущей темы сайта
   * 
   * @return StmThemeData
   */
  static public function getCurrentThemeData() {
  	if (($r = self::getCurrentThemeParams()) === false) return false;
    list($location, $id) = $r;
    return new StmThemeData(new StmDataSource($location), array('id' => $id));
  }
  
  /**
   * @return StmMenuData
   */
  static public function getCurrentMenuData() {
  	if (($r = self::getCurrentThemeParams()) === false) return false;
    list($location, $id) = $r;
    return new StmMenuData(new StmDataSource($location), array('id' => $id));
  }
  
  /**
   * @return StmThemeStructure
   */
  static public function getThemeStructure($siteSet, $design) {
    return O::get('StmThemeStructure', array(
      'siteSet' => $siteSet,
      'design' => $design
    ));
  }
  
  /**
   * @return StmMenuStructure
   */
  static public function getMenuStructure($menuType) {
    return O::get('StmMenuStructure', array(
      'menuType' => $menuType,
    ));
  }
  
  static public function getMenuStructures() {
    $r = array();
    foreach (Dir::dirs(STM_MENU_PATH) as $v) {
      $r[$v] = include STM_MENU_PATH.'/'.$v.'/structure.php';
    }
    return $r;
  }
  
  /**
   * @param string   Пример: ngn:12
   */
  static public function updateCurrentTheme($theme) {
    SiteConfig::updateSubVar('theme', 'enabled', true);
    SiteConfig::updateSubVar('theme', 'theme', $theme);
  }
  
  static public function getTags() {
    if (!Config::getVarVar('theme', 'enabled', true) or !Config::getVarVar('theme', 'theme')) return '';
    return
      '<link rel="stylesheet" type="text/css" media="screen, projection" href="/'.
      (empty($_GET['theme']) ?
        SFLM::getCachedUrl('s2/css/common/theme').'?'.BUILD :
        's2/css/common/theme?'.http_build_query($_GET['theme'])).
      '" />'.
      '<script type="text/javascript" src="/'.
      (empty($_GET['theme']) ?
        SFLM::getCachedUrl('s2/js/common/theme').'?'.BUILD :
        's2/js/common/theme?'.http_build_query($_GET['theme'])).
      '"></script>';
  }
  
  static public function cc() {
    SFLM::clearPathCache('s2/css/common/theme');
    SFLM::clearPathCache('s2/js/common/theme');
  }
  
}
