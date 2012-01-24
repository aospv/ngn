<?php

class FieldEStmThemeSelect extends FieldESelect {

  protected function defineOptions() {
  	foreach (Dir::dirs(STM_PATH.'/themes') as $v) {
  	  $theme = include STM_PATH.'/themes/'.$v.'/theme.php';
  	  $r['ngn:'.$v] = $theme['data']['title'];
  	}
    $this->options['options'] = array_merge(
      array('' => '— без темы —'),
      $r
    );
  }

}