<?php

DdFieldCore::registerType('usersChatLog', array(
  'dbType' => 'TEXT',
  'title' => 'Лог разговора пользователей',
  'order' => 400,
));

class FieldEUsersChatLog extends FieldETextarea {
}