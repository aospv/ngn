<?php

class RatingInstaller {
  
  /**
   * - Создаёт поле в структуре раздела
   * - Включает отображение рейтинга в настройках контроллера
   *
   * @param   integer   ID раздела, в котором нужно включить рейтинги
   */
  public function install($pageId) {
    $page = DbModelCore::get('pages', $pageId);
    $oFM = new DdFieldsManager($page['strName']);
    $oFM->createIfNotExists(array(
      'title' => 'Рейтинг',
      'name' => 'rating',
      'type' => 'num', 
      'system' => true,
      'oid' => 300
    ));
    $oFM->createIfNotExists(array(
      'title' => 'Средний рейтинг',
      'name' => 'rating_average',
      'type' => 'num',
      'system' => true,
      'oid' => 300
    ));
    $oFM->createIfNotExists(array(
      'title' => 'Оценка',
      'name' => 'rating_grade',
      'type' => 'num',
      'system' => true,
      'oid' => 300
    ));
    
    //$page->updateSettingsValue('showRating', 1);
  }
  
  /**
   * - Выключает отображение рейтинга в настройках контроллера
   *
   * @param   integer   ID раздела, в котором нужно включить рейтинги
   */
  public function uninstall($pageId) {
    NgnOrmCore::getTable('pages')->find($pageId)->
      updateSettingsValue('showRating', 0);
  }
  
}
