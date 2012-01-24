<?php

/**
 * структура, определенная здесь, определяет конечные данные
 *
 */
class StmThemeStructure extends Options2 {

  protected $requiredOptions = array('siteSet', 'design');
  
  public $str;
  
  protected function init() {
    $file = STM_DESIGN_PATH.'/'.$this->options['siteSet'].'/'.
      $this->options['design'].'/structure.php';
    if (!file_exists($file)) {
      throw new NgnException('Theme file "'.$file.'" does not exists');
    }
    $this->str = include $file;
    $this->str['commonFields'] = sfYaml::load('
-
  title: Главные настройки
  type: headerToggle
  name: mainSettings
-
  title: Название
  name: title
-
  title: Меню
  name: menu
  type: stmMenuSelect
-
  title: Абсолютные слайсы
  name: slices
  type: fieldSet
  fields:
    -
      name: id
      title: ID слайса
      type: name
    -
      name: title
      title: Название слайса
    -
      name: x
      title: Расстояние сверху от экрана
      type: pixels
    -
      name: y
      title: Расстояние слева от экрана
      type: pixels
# ====================================
-
  title: Общие настройки
  type: headerToggle
  name: contentSettings
-
  title: Размер обычного текста
  s: body
  p: font-size
  type: fontSize
-
  title: Цвет обычного текста
  s: body
  p: color
  type: color
-
  title: Шрифт обычного текста
  s: body
  p: font-family
  type: fontFamily
-
  title: Размер текста в блоках
  s: .pageBlocks .block
  p: font-size
  type: fontSize
-
  title: Размер текста в комментариях
  s: .msgs .text
  p: font-size
  type: fontSize
-
  title: Размер текста страницы записи
  s: .contentBody .t_wisiwig, .contentBody .f_descr, .contentBody .f_text
  p: font-size
  type: fontSize
-
  title: Шрифт текста страницы записи
  s: .contentBody .t_wisiwig, .contentBody .f_descr, .contentBody .f_text
  p: font-family
  type: fontFamily
-
  title: Цвета фона страниц
  s: body
  p: background-color
  type: color
-
  title: Цвет ссылок
  s: a
  p: color
  type: color
-
  title: Цвет ссылок при наведении
  s: a:hover
  p: color
  type: color
-
  title: Темно серый
  s: "#path a, .dgray, .dgray a, .apeform .iconsSet a, .pbSubMenu .active a"
  p: color
  type: color
-
  title: Серый
  s: "#path a:hover, .dgray a:hover, .gray, .gray a"
  p: color
  type: color
-
  title: Цвет заголовков полей
  s: .element b.title
  p: color
  type: color
-
  title: Шрифт заголовка 1 уровня
  s: h1
  p: font-family
  type: fontFamily
-
  title: Жирность заголовка 1 уровня
  s: "#pageTitle h1"
  p: font-weight
  type: fontWeight
-
  title: Размер заголовка 1 уровня
  s: "#pageTitle h1"
  p: font-size
  type: fontSize
-
  title: Стиль заголовок 1 уровня
  s: "#pageTitle h1"
  p: font-style
  type: fontStyle
-
  title: Цвет заголовка 1 уровня
  s: "#pageTitle h1"
  p: color
  type: color
-
  title: Отступ снизу от заголовка 1 уровня
  s: "#pageTitle h1"
  p: margin-bottom
  type: pixels
-
  title: Размер заголовка 2 уровня
  s: h2, .mceContentBody h2
  p: font-size
  type: fontSize
-
  title: Шрифт заголовка 2 уровня
  s: h2, .mceContentBody h2
  p: font-family
  type: fontFamily
-
  title: Цвет заголовка 2 уровня
  s: h2, .mceContentBody h2
  p: color
  type: color
-
  title: Жирность заголовка 2 уровня
  s: h2, .mceContentBody h2
  p: font-weight
  type: fontWeight
-
  title: Размер заголовка 3 уровня
  s: h3, .mceContentBody h3
  p: font-size
  type: fontSize
-
  title: Шрифт заголовка 3 уровня
  s: h3, .mceContentBody h3
  p: font-family
  type: fontFamily
-
  title: Цвет заголовка 3 уровня
  s: h3, .mceContentBody h3
  p: color
  type: color
-
  title: Жирность заголовка 3 уровня
  s: h3, .mceContentBody h3
  p: font-weight
  type: fontWeight
-
  title: Размер заголовка 4 уровня
  s: h4, .mceContentBody h4
  p: font-size
  type: fontSize
-
  title: Шрифт заголовка 4 уровня
  s: h4, .mceContentBody h4
  p: font-family
  type: fontFamily
-
  title: Цвет заголовка 4 уровня
  s: h4, .mceContentBody h4
  p: color
  type: color
-
  title: Жирность заголовка 4 уровня
  s: h4, .mceContentBody h4
  p: font-weight
  type: fontWeight
-
  title: Размер бордюра вокруг картинки
  s: .contentBody img, .mceContentBody img
  p: border-width
  type: borderSize
-
  title: Цвет фона цитат
  s: .msgs .text quote, blockquote
  p: background-color
  type: color
-
  title: Цвет текста цитат
  s: .msgs .text quote, blockquote
  p: color
  type: color
-
  title: Цвет бордюра цитат
  s: .msgs .text quote, blockquote
  p: border-color
  type: color
-
  title: Картинка слева от цитаты
  name: blockquoteImage
  type: image
-
  title: Разделитель заголовков блоков
  s: .pageBlocks .blockHeader
  p: border-bottom-width
  type: borderSize
-
  title: Размер заголовков блоков
  s: .pageBlocks h2
  p: font-size
  type: fontSize
-
  title: Шрифт заголовков блоков
  s: .pageBlocks h2
  p: font-family
  type: fontFamily
-
  title: Цвет заголовков блоков
  s: .pageBlocks h2
  p: color
  type: color
-
  title: Жирность заголовков блоков
  s: .pageBlocks h2
  p: font-weight
  type: fontWeight
-
  title: Цвет разделителей
  s: ".contentBody img, .pageBlocks .blockHeader, .items .item, #calendarHeader, .bordered.smIcons a:hover, a:hover.smIcons.bordered, .apeform .element, .pageBlocks .pbt_tags a, .avatar a, .thumb a, a.thumb"
  p: border-color
  type: color
-
  title: Цвет горизонтальных линий
  s: hr
  p: "color & background"
  type: color
-
  title: Цвет разделителей при наведении
  s: .avatar a:hover, .thumb a:hover, a:hover.thumb
  p: border-color
  type: color
-
  title: Меркер в тексте
  name: marker
  type: image
# =====================================================
-
  title: Размер текста хлебных крошек
  s: "#path"
  p: font-size
  type: fontSize
-
  title: Цвет текста хлебных крошек
  s: "#path, #path a"
  p: color
  type: color
# =====================================================
-
  title: Разное
  name: miscSettings
  type: headerToggle
-
  title: Темный фон
  name: black
  type: bool
-
  title: Полупрозрачные белые блоки
  name: whiteRoundBlocks
  type: bool
- 
  title: Отступ сверху в первой колонке
  name: col2TopPadding
  type: num
-
  title: Дополнительный отступ на сверху главной
  name: homeTopOffset
  type: num
-
  title: Радиус сглаживания аватарок и превьюх
  name: thumbRadius
  type: num
  
# =====================================================
-
  title: Настройки сайт-сета
  name: siteSetSettings
  type: headerToggle
-
  title: Отступ сверху от меню
  name: menuTopMargin
  type: pixels
-
  title: Отступ снизу от меню
  s: "#menu"
  p: margin-bottom
  type: pixels
-
  title: Использовать в качетсве отступа от меню ширину логотипа
  name: useLogoWidthAsMenuMargin
  type: bool
-
  title: Отступ справа от логотипа
  name: marginLeft
  type: pixels
-
  title: Отступ снизу от заголовка
  s: "#pageTitle"
  p: margin-bottom
  type: pixels
-
  title: Радиус закругления кнопок
  s: .btn, .btn2
  p: "border-radius & -moz-border-radius & -khtml-border-radius & -webkit-border-radius"
  type: pixels
-
  title: Цвет кнопок
  name: btnColor
  type: color
-
  title: Цвет кнопок при неведении
  s: a:hover.btn, b.btn
  p: background-color
  type: color
-
  title: Цвет шрифта копок
  name: btnTextColor
  type: color
-
  title: Цвет шрифта копок при неведении
  s: a:hover.btn
  p: color
  type: color
-
  title: Цвет кнопок №2
  s: a.btn2
  p: background-color
  type: color
-
  title: Цвет бордюра кнопок №2
  s: a.btn2
  p: border-color
  type: color
- 
  title: Цвет кнопок №2 при неведении
  s: a:hover.btn2, b.btn2
  p: background-color
  type: color
-
  title: Цвет шрифта кнопок №2
  s: a.btn2
  p: color
  type: color
-
  title: Цвет шрифта кнопок №2 при наведении
  s: a:hover.btn2
  p: color
  type: color
-
  title: Цвет ссылки в шапке
  name: headerLinkColor
  type: color
-
  title: Цвет ссылки в шапке при наведении мыши
  name: headerLinkColorOver
  type: color
# =====================================================
-
  title: Настройки блоков "Подразделы..."
  name: subPagesBlockSettings
  type: headerToggle
-
  title: Размер текста 1-го уровня
  s: .pbSubMenu .bcont > ul > li > a
  p: font-size
  type: fontSize
-
  title: Жирность текста 1-го уровня
  s: .pbSubMenu .bcont > ul > li > a
  p: font-weight
  type: fontWeight
-
  title: Цвет текста 1-го уровня
  s: .pbSubMenu .bcont > ul > li > a
  p: color
  type: color
-
  title: Цвет текста активного элемента
  s: .pbSubMenu .bcont li.active > a
  p: color
  type: color
-
  title: Меркер 1-го уровня
  name: marker1
  type: image
-
  title: Меркер 1-го уровня активного элемента
  name: marker1Active
  type: image
-
  title: Размер текста 2-го уровня
  s: .pbSubMenu .bcont > ul > li > ul > li > a
  p: font-size
  type: fontSize
-
  title: Жирность текста 2-го уровня
  s: .pbSubMenu .bcont > ul > li > ul > li > a
  p: font-weight
  type: fontWeight
-
  title: Цвет текста 2-го уровня
  s: .pbSubMenu .bcont > ul > li > ul > li > a
  p: color
  type: color
-
  title: Меркер 2-го уровня
  name: marker2
  type: image
-
  title: Отступ от пункта меню любого уровня снизу
  s: .pbSubMenu .bcont li
  p: margin-bottom
  type: pixels
-
  title: Отступ от пункта меню 1-го уровня снизу
  s: .pbSubMenu .bcont > ul > li
  p: margin-bottom
  type: pixels
-
  title: Отступ от подменю 2,3,4,.. уровня слева
  s: .pbSubMenu .bcont > ul > li ul
  p: margin-left
  type: pixels
-
  title: Отступ от подменю 2,3,4,.. уровня сверху
  s: .pbSubMenu .bcont > ul > li ul
  p: margin-top
  type: pixels
-
  title: Отступ от подменю 2,3,4,.. уровня снизу
  s: .pbSubMenu .bcont > ul > li ul
  p: margin-bottom
  type: pixels
  ');
    $this->str['commonFields'][] = array(
      'title' => 'Настройки дизайна «'.$this->str['title'].'»',
      'name' => 'design',
      'type' => 'headerToggle'
    );
    $this->str['fields'] = array_merge($this->str['commonFields'], $this->str['fields']);
  }

}
