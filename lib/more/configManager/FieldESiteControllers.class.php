<?php

class FieldESiteControllers extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = PageControllersCore::getTitles();
  }

}