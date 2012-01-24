<?php

class DbModelSlices extends DbModel {

  protected $cssClass = 'sliceType_wisiwig';
  
  protected $absolute;

  public function html($default = null) {
    if ($this->r['type'] == 'text') $this->setPlainTextMode(true);
    if (!$this->r['text'] and $default) $text = $default;
    else $text = $this->r['text'];
    return '<div id="slice_'.$this->r['id'].'" '.
      'class="slice pad-bottom '.
      'sliceId_'.$this->r['id'].' '.
      ($this->r['absolute'] ? 'sliceAbsolute ' : '').
      $this->cssClass.($this->isGlobal() ? ' slice_global' : '').'">'.
      '<div class="slice-title hidden">'.$this->r['title'].'</div>'.
      '<div class="slice-text">'.$text.'</div>'.
      '</div>'.
      '<div class="clear"><!-- --></div>';
   }
   
  protected function getName() {
    return preg_replace('/(.*)_\d+/', '$1', $this->r['id']);
  }
  
  protected function isGlobal() {
    return !strstr($this->r['id'], '_');
  }
  
  protected function setPlainTextMode($flag) {
    $this->cssClass = $flag ? ' sliceType_text' : ' sliceType_wisiwig';
  }

}