<?php

DdFieldCore::registerType('ddUserImage', array(
  'dbType' => 'VARCHAR',
  'dbLength' => 255,
  'title' => 'Изображение пользователя',
  'order' => 210
));

class FieldEDdUserImage extends FieldEImagePreview {
}