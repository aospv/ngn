<?php 

class DdoSettings {
  
  protected $pageModule, $pageModules;
  
  public function __construct($pageModule) {
    Misc::checkEmpty($pageModule);
    $this->pageModule = $pageModule;
    $this->pageModules = PageModuleCore::getAncestorNames($pageModule);
  }
  
  public function getLayouts() {
    $staticLayouts = array(
      array('adminItems', 'Ред. записей в админке'),
      array('eventsInfo', 'События'),
      array('siteItems', 'Список записей на сайте'),
      array('siteItem', 'Страница одной записи на сайте'),
      array('profile', 'Профиль'),
      array('pageBlock', 'Блок')
    );
    $staticLayouts = array_merge($staticLayouts, Config::getVar('ddoLayouts'));
    foreach ($staticLayouts as $v) $layouts[$v[0]]['title'] = $v[1];
    return $layouts;
  }
  
  protected function getVar($prefix, $suffix = null) {
    foreach ($this->pageModules as $module) {
      if (($r = Config::getVar(
      $prefix.'.'.$module.($suffix ? '.'.$suffix : ''), true)) !== false) {
        return $r;
      }
    }
    return false;
  }
  
  public function getShowAll() {
    return $this->getVar('ddoItemsShow');
  }
  
  public function getShow($layoutName) {
    if (($r = $this->getVar('ddoItemsShow')) === false) return false;
    return isset($r[$layoutName]) ? $r[$layoutName] : false;
  }

  /**
   * Возвращает все методы вывода для определенного поля
   *
   * @param   string  Имя поля
   * @return  array
   */
  public function getOutputMethods($fieldType) {
    $methods = array(
      array(
        'name' => '',
        'title' => 'по умолчанию'
      ),
      array(
        'name' => 'notitle',
        'title' => 'без заголовка'
      ),
    );
    if (($_methods = DdoMethods::getInstance()->field[$fieldType])) {
      foreach ($_methods as $name => $v) {
        $methods[] = array(
          'name' => $name,
          'title' => $v['title']
        );
      } 
    }
    return $methods;
  }
  
  /**
   * Возвращает все методы вывода текущей структуры для всех лейаутов
   * Пример:
   * array(
   *   'layoutName' => array(
   *     'title' => 'notitle',
   *     'userId' => 'avatar'
   *   )
   * )
   *
   * @return array
   */
  public function getOutputMethod() {
    return $this->getVar('ddoOutputMethod');
  }
  
  public function getAllowedFields($layoutName) {
    if (($r = $this->getShow($layoutName)) === false) return array();
    return array_keys($r);
  }
  
  public function getOrder($layoutName) {
    return $this->getVar('ddoFieldOrder', $layoutName);
  }
  
  /**
   * Сохраняет настройки вывода полей для файлов
   *
   * @param   array   Пример:  
   * array(
   *   'tpl/admin/pages/items/albums.php' => array(
   *     'title' => 1
   *   )
   * )
   *         
   */
  public function updateShow($values) {
    SiteConfig::updateVar('ddoItemsShow.'.$this->pageModule, $values);
  }
  
  public function updateOutputMethod($values) {
    SiteConfig::updateVar('ddoOutputMethod.'.$this->pageModule, $values);
  }
  
  /**
   * @param   array   Пример:
   * array(
   *   'fieldName1' => 10,
   *   'fieldName1' => 20
   * )
   */
  public function updateOrderIds($oids, $layoutName) {
    SiteConfig::updateVar('ddoFieldOrder.'.$this->pageModule.'.'.$layoutName, $oids);
  }
  
  public function delete() {
    Dir::deleteFiles(SITE_PATH."/config/vars", '*.'.$this->pageModule.'.*');
  }
  
  public function rename($newModule) {
    SiteConfig::renameVar('ddoFieldOrder', $this->pageModule, $newModule);
    SiteConfig::renameVar('ddoItemsShow', $this->pageModule, $newModule);
    SiteConfig::renameVar('ddoOutputMethod', $this->pageModule, $newModule);
  }
  
}