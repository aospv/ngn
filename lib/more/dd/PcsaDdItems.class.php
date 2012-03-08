<?php

class PcsaDdItems extends Pcsa {

  public function action(array $initSettings) {
    if ($initSettings['manualOrder']) {
      if (!O::get('DdFields', $initSettings['strName'])->exists('oid')) {
        O::get('DdFieldsManager', $initSettings['strName'])->create(array(
          'title' => LANG_ORDER_NUM,
          'name' => 'oid',
          'type' => 'num',
          'system' => true,
          'oid' => 300
        ));
      }
      $initSettings['order'] = 'oid';
    }
    return $initSettings;
  }

}