<?php

class StmThemeDataSet extends Options2 {

  public $items = array();
  public $requiredOptions = array('siteSet');
  
  public function init() {
    foreach (StmLocations::getThemeFolders() as $location => $themeFolder) {
      foreach (Dir::dirs($themeFolder.'/'.$this->options['siteSet']) as $design) {
        $oSDS = new StmDataSource(array(
          'location' => $location,
          'siteSet' => $this->options['siteSet'],
          'design' => $design
        ));
        foreach (Dir::files($themeFolder.'/'.$this->options['siteSet'].'/'.
        $design.'/data') as $id) {
          $id = str_replace('.php', '', $id);
          $this->items[] = new StmThemeData($oSDS, array('id' => $id));
        }
      }
    }
  }
  
  public function getOptions() {
    $options = array();
    foreach ($this->items as $v) {
      /* @var $v StmThemeData */
      $options[$v->oSDS->options['location'].':'.$v->oSDS->options['design'].':'.$v->id] = 
        $v->oSDS->structure['title'].' / '.$v->data['data']['title'];
    }
    return $options;
  }
  

}
