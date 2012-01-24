<?php

class FieldEListSlicesType extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array(
      '' => 'общий для раздела',
    );
    foreach (O::get('DdFields', $this->oForm->strName)->getFields() as $v) {
      $this->options['options']['v_'.$v['name']] =
        'отдельный для каждой выборки по полю «'.$v['title'].'»';
    }
    foreach (O::get('DdFields', $this->oForm->strName)->getTagFields() as $v) {
      $this->options['options']['tag_'.$v['name']] =
        'отдельный слайс для каждого тега «'.$v['title'].'»';
    }
  }

}
