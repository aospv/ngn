<?php

class StmMenuDataSet extends Options2 {
  
  public $items = array();
  
  public function init() {
    foreach (StmLocations::getMenuFolders() as $location => $menuFolder) {
      foreach (Dir::dirs($menuFolder) as $menuType) {
        foreach (Dir::files($menuFolder.'/'.$menuType.'/data') as $id) {
          $id = str_replace('.php', '', $id);
          $this->items[] = new StmMenuData(new StmMenuDataSource(array(
            'location' => $location,
            'menuType' => $menuType
          )), array('id' => $id));
        }
      }
    }
  }
  
}
