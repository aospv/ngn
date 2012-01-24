<?php

class FieldEMyProfilePagesMultiselect extends FieldEMultiselect {

  protected function defineOptions() {
    parent::defineOptions();
    $this->options['options'] =
      db()->selectCol(
        'SELECT id AS ARRAY_KEY, title FROM pages WHERE controller=?', 'myProfile');
  }

}
