<?php

return array(
  'title' => 'Full Header',
  'fields' => array(
    array(
      'title' => 'Фоновая картика',
      'name' => 'bgImage',
      'type' => 'image'
    ),
    array(
      'title' => 'Фон шапки',
      'name' => 'headerBgImage',
      'type' => 'image',
    ),
    array(
      'title' => 'Логотип',
      'name' => 'logoImage',
      'type' => 'image'
    ),
    array(
      'title' => 'Отступ от логотипа сверху',
      'name' => 'logoMarginTop',
      'type' => 'num',
    ),
    array(
      'title' => 'Отступ от логотипа слева',
      'name' => 'logoMarginLeft',
      'type' => 'num',
    ),
    array(
      'title' => 'Отсутуп от меню сверху',
      'name' => 'menuMarginTop',
      'type' => 'num'
    ),
    array(
      'title' => 'Отсутуп от меню снизу',
      'name' => 'menuMarginBottom',
      'type' => 'num'
    ),
    array(
      'title' => 'Отступ от меню слева',
      'name' => 'menuMarginLeft',
      'type' => 'num',
    ),
    array(
      'title' => 'Меню',
      'name' => 'menu',
      'type' => 'select',
      'options' => 'stmMenuSelect'
    ),
  )
);
