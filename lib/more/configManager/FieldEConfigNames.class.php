<?php

class FieldEConfigNames extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array('' => '—');
    $this->options['options'] += SiteConfig::getTitles('vars');
  }

}