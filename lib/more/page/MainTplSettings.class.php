<?php

class MainTplSettings {
  
  static public function get($tplName = 'main', $pageId = 0) {
    if (!($r = Settings::get('mainTplSettings_'.$tplName.'_'.$pageId))) {
      // Если не существует настроек для раздела, получаем настройки по умолчанию
      if (!($r = Settings::get('mainTplSettings_'.$tplName.'_0'))) {
        // Если нет настроек по умолчанию, получаем из свойств полей
        foreach (self::getFields($tplName) as $v) {
          if (isset($v['default'])) 
            $r[$v['name']] = $v['default'];
        }
      }
    }
    return $r;
  }
  
  static public function delete($tplName, $pageId) {
    Settings::delete('mainTplSettings_'.$tplName.'_'.$pageId);
    self::deleteBlocks($pageId);
  }
  
  static public function getFields($tplName) {
    if (!preg_match(
      '/'.
      '^\s*\*[^\n]*beginTplSettings[^\n]*\n'.
      '(.*)'.
      '^\s*\*[^\n]*endTplSettings[^\n]*\n'.
      '/sm',
      file_get_contents(Tt::exists($tplName)), $m)
    ) return array(array(
      'type' => 'header',
      'title' => 'Нет полей'
    ));
    $a = preg_replace('/^ \* (.*)$/m', '$1', $m[1]);
    return eval('return '.$a.';');
  }
  
  static public function save($tplName, $pageId, $settings) {
    Settings::set('mainTplSettings_'.$tplName.'_'.$pageId, $settings);
    self::deleteBlocks($pageId);
  }
  
  static protected function deleteBlocks($pageId) {
    if ($pageId)
      db()->query('DELETE FROM page_blocks WHERE ownPageId=?d', $pageId);
    else
      db()->query('DELETE FROM page_blocks WHERE static=1'); 
    NgnCache::clean();
  }
  
}