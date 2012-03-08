<?php

class FieldVFullName extends FieldVAbstract {

  public function error($v) {
    return preg_match('/\S+ \S+ \S+/', $v) ? false : 'Неправильный формат';
  }

}
