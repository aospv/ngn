<?php

Lang::load('admin');

class DdFieldOptions {
  
  static public function order($strName) {
    $options = array('' => '— '.LANG_NOTHING_SELECTED.' —');
    $o = new DdFields($strName, array(
      'getSystem' => true,
      'getDisallowed' => true
    ));
    $fields = $o->getFields();
    foreach ($fields as $v) {
      $options[$v['name']] = $v['title'];
      $options[$v['name'].' DESC'] = $v['title'].' ['.LANG_REVERSE_ORDER.']';
    }
    $options['rand()'] = 'Случайным образом';
    return $options;
  }
  
  static public function date($strName) {
    return array_merge(
      array('' => '— '.LANG_NOTHING_SELECTED.' —'),
      Arr::get(O::get('DdFields', $strName,
        array(
          'getSystem' => true,
          'getDisallowed' => true
        ))->getDateFields(), 'title', 'name')
    );
  }
  
  static public function fields($strName) {
    return array_merge(
      array('' => '— '.LANG_NOTHING_SELECTED.' —'),
      Arr::get(O::get('DdFields', $strName,
        array(
          'getSystem' => true,
          'getDisallowed' => true
        ))->getFields(), 'title', 'name')
    );
  }
  
}
