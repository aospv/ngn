<?php

class FieldEStoreOrderControllerSuffix extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] =
      Arr::get(ClassCore::getDescendants('CtrlPageVStoreOrder'), 'title', 'name');
  }

}
