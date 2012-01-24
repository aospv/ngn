<?php

class DbModelPageBlocks extends DbModel {

  static public $serializeble = array(
    'settings'
  );

  static public function afterCreateUpdate($id) {
    PageBlockCore::cc($id);
  }

}
