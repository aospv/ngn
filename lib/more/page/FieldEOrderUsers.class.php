<?php

class FieldEOrderUsers extends FieldESelect {

  protected function defineOptions() {
    $fields = array(
      'dateCreate' => array(
        'name' => 'dateCreate',
        'title' => LANG_CREATION_DATE
      ),
      'dateUpdate' => array(
        'name' => 'dateUpdate',
        'title' => LANG_UPDATE_DATE
      )
    );
    $this->options['options'] = array('' => '— '.LANG_NOTHING_SELECTED.' —');
    foreach ($fields as $v) {
      $this->options['options'][$v['name']] = $v['title'];
      $this->options['options'][$v['name'].' DESC'] = $v['title'].' ['.LANG_REVERSE_ORDER.']';
    }
  }

}
