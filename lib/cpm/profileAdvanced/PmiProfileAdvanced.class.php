<?php

class PmiProfileAdvanced extends PmiProfile {
  
  public $title = 'Профиль (социальный)';
  public $oid = 125;
  
  protected $ddFields = array(
    array(
      'title' => 'Имя',
      'name' => 'name',
      'type' => 'typoText'
    ),
    array(
      'title' => 'Фото',
      'name' => 'image',
      'type' => 'imagePreview'
    ),
    array(
      'title' => 'ICQ#',
      'name' => 'icq',
      'type' => 'num'
    ),
    array(
      'title' => 'Ссылки на личные страницки',
      'name' => 'urls',
      'type' => 'urls'
    ),
    array(
      'title' => 'Город',
      'name' => 'city',
      'type' => 'typoText',
    ),
    array(
      'title' => 'Пол',
      'name' => 'sex',
      'type' => 'radio',
      'options' => array(
        'm' => 'мужской',
        'w' => 'женский',
      )
    ),
    array(
      'title' => 'О себе',
      'name' => 'text',
      'type' => 'typoText'
    )
  );
  
}
