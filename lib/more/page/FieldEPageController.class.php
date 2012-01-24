<?php

class FieldEPageController extends FieldERequestFieldsSelect {

  protected function defineOptions() {
    $this->options = array(
      'title' => 'Контроллер',
      'options' => (
        array('' => '— '.LANG_NOTHING_SELECTED.' —') +
        PageControllersCore::getTitles()
      ),
      'requestedNames' => array('settings'),
      'action' => 'ajax_controllerRequiredFields'
    );
  }

}