<?php

return array(
  'title' => 'Простой 2',
  'fields' => array(
    array(
      'title' => 'Логотип',
      'name' => 'logo',
      'type' => 'image',
      'tune' => true
    ),
    array(
      'title' => 'Фоновая картинка лейаута',
      'name' => 'layoutBg',
      'type' => 'image'
    ),
    array(
      'title' => 'Фоновая картинка контейнера',
      'name' => 'containerBg',
      'type' => 'image'
    ),
    array(
      'title' => 'Сдвиг фоновой картинки сверху',
      'name' => 'backgroundTopOffset',
      'type' => 'pixels'
    ),
    array(
      'title' => 'Отступ сверху от логотипа',
      'name' => 'logoTopMargin',
      'type' => 'pixels'
    ),
    array(
      'title' => 'Фоновая картинка заголовочного блока',
      'name' => 'mainHeaderBg',
      'type' => 'image'
    ),
    array(
      'title' => 'Фоновая картинка футера',
      'name' => 'footerBg',
      'type' => 'image'
    ),
    array(
      'title' => 'Цвет текста футера',
      's' => '#footer',
      'p' => 'color',
      'type' => 'color'
    ),
    array(
      'title' => 'Высота футера',
      'name' => 'footerHeight',
      'type' => 'pixels'
    ),
    array(
    'title' => 'Отступ от границы заголовочного блока до текста',
      's' => '.mainHeader',
      'p' => 'padding',
      'type' => 'pixels'
    ),
    array(
      'title' => 'Отступ от границы заголовочного блока до текста слева',
      's' => '.mainHeader',
      'p' => 'padding-left',
      'type' => 'pixels'
    ),
    array(
      'title' => 'Отступ слева от главного контентного блока',
      's' => '.mainBody',
      'p' => 'margin-left',
      'type' => 'pixels'
    ),
    array(
      'title' => 'Отступ сверху от главного контентного блока',
      's' => '.mainBody',
      'p' => 'margin-top',
      'type' => 'pixels'
    ),
    array(
      'title' => 'Настройки главной страинцы',
      'name' => 'homepageSettings',
      'type' => 'headerToggle'
    ),
    array(
      'title' => 'Фоновая картинка лейаута',
      'name' => 'homeLayoutBg',
      'type' => 'image'
    ),
    array(
      'title' => 'Фоновая картинка контейнера',
      'name' => 'homeContainerBg',
      'type' => 'image'
    )
  )
);
