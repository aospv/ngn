<?php

DdFieldCore::registerType('ddTags', array(
  'dbType' => 'VARCHAR',
  'dbLength' => 255,
  'title' => 'Тэги (через запятую)',
  'order' => 210,
  'tags' => true,
  'tagsItemsDirected' => true
));

class FieldEDdTags extends FieldEText {
  
  protected $useDefaultJs = true;
  
}
