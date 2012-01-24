<?php

class DbModelUserSettings extends DbModel {

  static public $serializeble = array(
    'settings'
  );
  
  static public $hasAutoIncrement = false;
  
  static public $hasDefaultDateFields = false;
  
}

class DbModelUserStoreSettings extends DbModelUserSettings {}
