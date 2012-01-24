<?

return array(
  'title' => 'Колоночное',
  'properties' => array(
    'linkImage',
    'linkImageHover',
    'linkImageActive',
    'columnWidth',
    'menuWidth',
    array(
      'title' => 'Отступ ячейки меню до текста',
      's' => '.mainmenu a span',
      'p' => 'padding',
      'type' => 'pixels'
    ),
    array(
      'title' => 'Отступ ячейки меню до текста слева',
      's' => '.mainmenu a span',
      'p' => 'padding-left',
      'type' => 'pixels'
    ),
    array(
      'title' => 'Отступ снизу от ячейки меню',
      's' => '.mainmenu li',
      'p' => 'margin-bottom',
      'type' => 'pixels'
    )
  )
);
