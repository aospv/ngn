<?php

class StmCss {
  
  public $css = '';
  
  protected function addComment($s) {
    if (IS_DEBUG !== true) return;
    $this->css .= $s;
  }
  
  public function addNoFileComment($file) {
    $this->addComment("\n/*======== File '$file' does not exists ========*/\n");
  }
  
  public function addNoDataComment($v) {
    $this->addComment("\n/*======== No data: $v ========*/\n");
  }
  
  public function addHeaderComments($title) {
    $this->addComment("\n\n/*======== $title =======*/\n\n");
  }
  
  public function addAutoCss(StmData $oSTD, $name = null) {
    if (empty($oSTD->data['cssData'])) return;
    $this->addHeaderComments($name.' auto css');
    $d = array();
    foreach ($oSTD->data['cssData'] as $v) {
      $d[$v['s']][$v['p']] = $v['v'];
    }
    foreach ($d as $selector => $params) {
      $this->css .= "\n".$selector." {\n".Tt::enum($params, "\n", '$k.`: `.$v.`;`')."\n}\n";
    }
  }
  
  public function addCssFile($file, StmData $oSTD) {
    $this->addHeaderComments(str_replace('.php', '', str_replace(STM_PATH, '', $file)));
    $data = $oSTD->data['data'];
    $data['cssData'] = $oSTD->data['cssData'];
    Err::noticeSwitch(false);
    $this->css .= Misc::getIncluded($file, $data);
    Err::noticeSwitchBefore();
  }
  
  static public $propFields = array(
    'fontSize' => array(
      'title' => 'Размер шрифта',
      'type' => 'fontSize'
    ),
    'fontSize2' => array(
      'title' => 'Размер шрифта меню 2-го уровня',
      'type' => 'fontSize'
    ),
    'submenuWidth' => array(
      'title' => 'Ширина меню 2-го уровня',
      'required' => true,
      'type' => 'num',
    ),
    'marginRight' => array(
      'title' => 'Отступ справа'
    ),
    'fontFamily' => array(
      'title' => 'Шрифт',
      'type' => 'fontFamily',
      's' => '.mainmenu',
      'p' => 'font-family',
    ),
    'fontStyle' => array(
      'title' => 'Наклонный',
      'type' => 'fontStyle',
      's' => '.mainmenu',
      'p' => 'font-style',
    ),
    'menuSize' => array(
      'title' => 'Размер меню',
      'type' => 'select',
      'required' => true,
      'options' => array(
        'sm' => 'Маленький',
        'md' => 'Средний',
        'lg' => 'Большой'
      )
    ),
    'menuHeight' => array(
      'title' => 'Высота меню',
      'type' => 'num'
    ),
    'itemHeight' => array(
      'title' => 'Высота ячейки меню'
    ),
    'spanMarginTop' => array(
      'title' => 'Отступ от ячейки меню до верхнего края меню'
    ),
    'fontWeight' => array(
      'title' => 'Жирность',
      'type' => 'select',
      'options' => array(
        'normal' => 'нормальный',
        'bold' => 'жирный'
      )
    ),
    'fontWeight2' => array(
      'title' => 'Жирность меню 2-го уровня',
      'type' => 'select',
      'options' => array(
        'normal' => 'нормальный',
        'bold' => 'жирный'
      )
    ),
    'bgColor' => array(
      'title' => 'Фон',
      'type' => 'color'
    ),
    'bgColorActive' => array(
      'title' => 'Фон активной ачейки меню',
      'type' => 'color',
    ),
    'bgColorOver' => array(
      'title' => 'Фон ячейки меню с наведеной мышью',
      'type' => 'color',
    ),
    'colorActive' => array(
      'title' => 'Цвет шрифта активной ячейки меню',
      's' => '.mainmenu .active a',
      'p' => 'color',
      'type' => 'color'
    ),
    'backgroundImage' => array(
      'title' => 'Фоновое изображение',
      'type' => 'image'
    ),
    'borderColor' => array(
      'title' => 'Цвет бордюра',
      'type' => 'color'
    ),
    'color' => array(
      'title' => 'Цвет шрифта неактивной ячейки меню',
      's' => '.mainmenu a',
      'p' => 'color',
      'type' => 'color'
    ),
    'color2' => array(
      'title' => 'Цвет шрифта неактивной ячейки меню 2-го уровня',
      'type' => 'color'
    ),
    'colorHover' => array(
      'title' => 'Цвет шрифта при наведении мыши',
      's' => '.mainmenu a:hover',
      'p' => 'color',
      'type' => 'color'
    ),
    'radius' => array(
      'title' => 'Радиус загругления углов',
      'type' => 'num'
    ),
    'textMarginTop' => array(
      'title' => 'Отступ сверху от текста меню 1-го уровня',
      's' => '.mainmenu > ul > li > a span',
      'p' => 'padding-top',
      'type' => 'pixels'
    ),
    'itemMarginRight' => array(
      'title' => 'Отступ от ячейки меню справа',
      's' => '.mainmenu > ul li',
      'p' => 'margin-right',
      'type' => 'pixels'
    ),
    'menuBorderWidth' => array(
      'title' => 'Размер бордюра меню',
      'type' => 'num'
    ),
    'submenuBorderWidth' => array(
      'title' => 'Размер разделителей меню 2-го уровня',
      'type' => 'num'
    ),
    'submenuBorderColor' => array(
      'title' => 'Цвет разделителей меню 2-го уровня',
      'type' => 'color'
    ),
    'roundBorderColor' => array(
      'title' => 'Цвет закругленного бордюра',
      'type' => 'color'
    ),
    'roundBorderColorOver' => array(
      'title' => 'Цвет закругленного бордюра при наведении мыши',
      'type' => 'color'
    ),
    'roundBorderColorActive' => array(
      'title' => 'Цвет закругленного бордюра активной ячейки',
      'type' => 'color'
    ),
    'linkImage' => array(
      'title' => 'Изображение фона обычной ссылки меню',
      'type' => 'image'
    ),
    'linkImageHover' => array(
      'title' => 'Изображение фона обычной ссылки меню при наведении',
      'type' => 'image'
    ),
    'linkImageActive' => array(
      'title' => 'Изображение фона активной ссылки меню при наведении',
      'type' => 'image'
    ),
    'columnWidth' => array(
      'title' => 'Ширина колонки',
      's' => '.mainmenu li',
      'p' => 'width',
      'type' => 'pixels'
    ),
    'menuWidth' => array(
      'title' => 'Ширина меню',
      's' => '.mainmenu',
      'p' => 'width',
      'type' => 'pixels'
    ),
    'barColor' => array(
      'title' => 'Цвет панели меню',
      'type' => 'color'
    )
  );
  
  /**
   * Theme static files folder name
   * 
   * @var string
   */
  const FOLDER_NAME = 'i';
  
  static public function extendImageUrls(StmData $oSD) {
    foreach ($oSD->getStructure()->str['fields'] as $v) {
      if (isset($v['type']) and $v['type'] == 'image') {
        if (!empty($oSD->data['data'][$v['name']])) {
          list($w, $h) = getimagesize($oSD->getThemePath().'/'.
            self::FOLDER_NAME.'/'.$oSD->getName().'/'.$oSD->data['data'][$v['name']]);
          $oSD->data['data'][$v['name']] = array(
            'url' => '/'.$oSD->getThemeWpath().'/'.self::FOLDER_NAME.'/'.$oSD->getName().'/'.
              $oSD->data['data'][$v['name']],
            'w' => $w,
            'h' => $h
          );
        }
      }
    }
  }
  
  static function cleanColors(StmData $oSD) {
    foreach (Arr::filter_by_value(
    $oSD->getStructure()->str['fields'], 'type', 'color') as $v) {
      if (isset($oSD->data['data'][$v['name']]))
        $oSD->data['data'][$v['name']] =
          str_replace('#', '', $oSD->data['data'][$v['name']]);
    }
  }
  
  static function url($url, $ext = 'png') {
    // Очистка параметров цветов
    $url = preg_replace('/\/#/', '/', $url);
    return UrlCache::get($url, $ext);
  }
  
}
