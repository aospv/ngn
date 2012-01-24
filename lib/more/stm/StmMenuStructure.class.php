<?php

class StmMenuStructure extends Options2 {

  protected $requiredOptions = array('menuType');
  
  public $str;
  
  protected function init() {
    $structure = include 
      STM_MENU_PATH.'/'.$this->options['menuType'].'/structure.php';
    $this->str['fields'] = $this->getBaseFields();
    $this->str['fields'][] = array(
      'type' => 'headerToggle',
      'title' => 'Настройки меню «'.$structure['title'].'»'
    );
    foreach ($structure['properties'] as $prop) {
      if (!is_array($prop)) {
        $v = StmCss::$propFields[$prop];
        if (!isset($v['s'])) $v['name'] = $prop;
      } else {
        $v = $prop;
      }
      $this->str['fields'][] = $v;
    }
  }
  
  protected function getBaseFields() {
    return sfYaml::load('
-
  title: Общие настройки
  type: headerToggle
  name: contentSettings
# ====================================
-
  title: Количество уровней
  name: levels
  type: select
  options:
    1: 1
    2: 2
-
  title: Размер текста меню 1 уровня
  s: .mainmenu a
  p: font-size
  type: fontSize
-
  title: Цвет текста меню 1 уровня
  s: .mainmenu a
  p: color
  type: color
-
  title: Цвет текста меню 1 уровня при наведении мыши
  s: .mainmenu li.over > a
  p: color
  type: color
-
  title: Цвет текста активной ячейки меню 1 уровня
  s: .mainmenu > ul > li.active > a
  p: color
  type: color
-
  title: Жирность текста меню 1 уровня
  s: .mainmenu a
  p: font-weight
  type: fontWeight
-
  title: Размер текста меню 2 уровня
  s: .mainmenu li li a
  p: font-size
  type: fontSize
-
  title: Цвет текста меню 2 уровня
  s: .mainmenu li li a
  p: color
  type: color
-
  title: Цвет текста меню 2 уровня при наведении мыши
  s: .mainmenu li li a:hover
  p: color
  type: color
-
  title: Цвет текста активной ячейки меню 2 уровня
  s: .mainmenu li li.active a
  p: color
  type: color
-
  title: Жирность текста меню 2 уровня
  s: .mainmenu li li a
  p: font-weight
  type: fontWeight
-
  title: Ширина меню 2-го уровня
  name: submenuWidth
  type: num
  
-
  title: Отступ от ячейки до верхнего края
  name: spanPaddingTop
  type: num
-
  title: Отступ от ячейки до левого края
  name: spanPaddingLeft
  type: num
-
  title: Отступ от ячейки до правого края
  name: spanPaddingRight
  type: num
-
  title: Отступ от ячейки до нижнего края
  name: spanPaddingBottom
  type: num  
-
  title: Отступ от ячейки 2 уровня до верхнего края
  s: .mainmenu li li a span
  p: padding-top
  type: pixels
  important: 1
');
  }

}