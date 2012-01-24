<?php

class PageLayout {
  
  static public $maxSpan = 24;
  static public $minSpan = 5;
  static public $spanWidth = 30;
  
  static public function getTypes() {
    return Arr::get(self::getLayouts(), 'descr', 'KEY');
  }
  
  static public function getLayouts() {
    $contentCol = array(
      'type' => 'content',
      'allowBlocks' => false
    );
    $blocksCol = array(
      'type' => 'blocks',
      'allowBlocks' => true,
      'allowGlobalBlocks' => true
    );
    $blocks2Col = array(
      'type' => 'blocks',
      'allowBlocks' => true,
      'allowGlobalBlocks' => false
    );
    $layouts = array(
      1 => array(
        'descr' => 'основная',
        'cols' => array(
          1 => $contentCol + array('span' => 24)
        )
      ),
      2 => array(
        'descr' => 'блочная - основная',
        'cols' => array(
          1 => $blocksCol + array('span' => 5),
          2 => $contentCol + array('span' => 19)
        )
      ),
      3 => array(
        'descr' => 'блочная - основная',
        'cols' => array(
          1 => $contentCol + array('span' => 19),
          2 => $blocksCol + array('span' => 5)
        )
      ),
      4 => array(
        'descr' => 'блочная - основная - блочная',
        'cols' => array(
          1 => $blocksCol + array('span' => 5),
          2 => $contentCol + array('span' => 14),
          3 => $blocksCol + array('span' => 5)
        )
      ),
      5 => array(
        'descr' => 'основная - блочная - блочная',
        'cols' => array(
          1 => $contentCol + array('span' => 14),
          2 => $blocksCol + array('span' => 5),
          3 => $blocksCol + array('span' => 5)
        )
      ),
      6 => array(
        'descr' => 'блочная - блочная - блочная',
        'cols' => array(
          1 => $blocks2Col + array('span' => 8),
          2 => $blocks2Col + array('span' => 8),
          3 => $blocks2Col + array('span' => 8)
        )
      ),
      7 => array(
        'descr' => 'блочная - блочная - блочная - блочная',
        'cols' => array(
          1 => $blocks2Col + array('span' => 6),
          2 => $blocks2Col + array('span' => 6),
          3 => $blocks2Col + array('span' => 6),
          4 => $blocks2Col + array('span' => 6)
        )
      ),
      8 => array(
        'descr' => 'блочная',
        'cols' => array(
          1 => $blocks2Col + array('span' => 12),
          2 => $blocks2Col + array('span' => 12)
        )
      ),
      9 => array(
        'descr' => 'блочная - основная - блочная (#2)',
        'cols' => array(
          1 => $blocksCol + array('span' => 5),
          2 => $contentCol + array('span' => 19),
          3 => $blocksCol + array('span' => 5)
        )
      ),
      10 => array(
        'descr' => 'блочная - блочная - блочная (#2)',
        'cols' => array(
          1 => $blocks2Col + array('span' => 5),
          2 => $blocks2Col + array('span' => 19),
          3 => $blocks2Col + array('span' => 5)
        )
      ),
      11 => array(
        'descr' => 'основная - блочная',
        'cols' => array(
          1 => $contentCol + array('span' => 10),
          2 => $blocksCol + array('span' => 9)
        )
      ),
      12 => array(
        'descr' => 'основная - блочная',
        'cols' => array(
          1 => $blocksCol + array('span' => 24),
          2 => $blocksCol + array('span' => 3),
          3 => $blocksCol + array('span' => 7),
          4 => $blocksCol + array('span' => 7),
          5 => $blocksCol + array('span' => 7)
        )
      ),
      13 => array(
        'descr' => 'блочная - блочная - блочная',
        'cols' => array(
          1 => $blocks2Col + array('span' => 6),
          2 => $blocks2Col + array('span' => 12),
          3 => $blocks2Col + array('span' => 6)
        )
      ),
    );
    foreach ($layouts as $k => &$layout) {
      $layouts[$k]['n'] = $k;
      $layouts[$k]['allowGlobalBlocks'] = 
        Arr::subValueExists($layouts[$k]['cols'], 'allowGlobalBlocks', true);
    }
    return $layouts;
  }
  
  static public function getColsByLayout($pageId) {
    $layouts = self::getLayouts();
    $layout = $layouts[PageLayoutN::get($pageId)];
    return $layout['cols'];
  }
    
  static function allowGlobalBlocks($pageId) {
    $layouts = self::getLayouts();
    return $layouts[PageLayoutN::get($pageId)]['allowGlobalBlocks'];
  }
  
  static public function getContentColWidth($pageId) {
    $layouts = self::getLayouts();
    $r = Arr::getValueByKey($layouts[PageLayoutN::get($pageId)]['cols'], 'type', 'content');
    return ($r['span']*30) + (($r['span']-1)*10);
  }
  
}
