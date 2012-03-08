<?php

class Slice {

  /**
   * @return DbModelSlices
   */
  static public function getOrCreate(array $data) {
    if (($r = DbModelCore::get('slices', $data['id'])) !== false) {
      return $r;
    }
    DbModelCore::create('slices', $data);
    return DbModelCore::get('slices', $data['id']);
  }
  
  static public function html($id, $title, array $options = array()) {
    $default = empty($options['default']) ? '' : $options['default'];
    $type = empty($options['type']) ? 'html' : $options['type'];
    return self::getOrCreate(array(
      'id' => $id,
      'title' => $title,
      'type' => $type,
      'absolute' => !empty($options['absolute'])
    ))->setProp('allowAdmin', !empty($options['allowAdmin']))->html();
  }
  
  static public function deleteByPageId($pageId) {
    array_map(function($id) {
      DbModelCore::delete('slices', $id);
    }, db()->ids('slices', 'pageId=?d', $pageId));
  }
  
  /**
   * Сохраняет позицию для абсолютных слайсов
   * 
   * @param   string  ID лайса
   * @param   array   array('x' => .123, 'y' => 123)
   */
  static public function savePos($id, array $s) {
    Arr::checkEmpty($s, array('x', 'y'));
    $oSTD = StmCore::getCurrentThemeData();
    $k = Arr::get_key_by_value($oSTD->data['data']['slices'], 'id', $id);
    $oSTD->data['data']['slices'][$k]['x'] = $s['x'];
    $oSTD->data['data']['slices'][$k]['y'] = $s['y'];
    $oSTD->save();
  }
  
}