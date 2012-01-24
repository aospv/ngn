<?php

class FieldEBirthDate extends FieldEDate {

  protected function validate3() {
    if ($this->m[3] > date('Y')-7) $this->error = 'Вы слишком молоды';
  }

}