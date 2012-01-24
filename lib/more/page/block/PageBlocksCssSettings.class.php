<?php

class PageBlocksCssSettings {
  
  static public function get($pageId) {
    if (!($arr = Config::getVar('pageBlocks_'.$pageId, true)))
      $arr = Config::getVar('pageBlocksSettings');
    Arr::filter_empties_strings($arr);
    return $arr;
  }
  
}
