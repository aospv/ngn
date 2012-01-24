<?php

class FieldEItemsPagesMultiselect extends FieldEMultiselect {

  protected function defineOptions() {
    parent::defineOptions();
    foreach (db()->select('SELECT id, title, controller FROM pages') as $v) {
      if (ClassCore::nameToClass('CtrlPage', $v['controller'], 'CtrlPageDdItems'))
        $this->options['options'][$v['id']] = $v['title'];
    }
  }

}
