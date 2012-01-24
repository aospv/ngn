<?php

class FieldEUrls extends FieldETextarea {

  protected function defineOptions() {
    $this->options['help'] = 'Вводите каждую новую ссылку с новой строки';
  }
  
  protected function validate2() {
    foreach (explode("\n", $this->options['value']) as $link)
      if (!Misc::validUrl($link))
        $this->error = "Неправильный формат ссылки <b>$link</b>";
  }

}