<?php

class SiteCore {

  static public function clearTemp() {
    Dir::clear(TEMP_PATH);
  }

}
