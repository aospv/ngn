<?php

class FieldEImagePreview extends FieldEImage {

  public function defineOptions() {
    parent::defineOptions();
    $this->options['currentFileClass'] = 'image lightbox';
    $this->options['rowClass'] = 'elImagePreview';
  }
  
  protected function getCurrentValue() {
    return Misc::getFilePrefexedPath(parent::getCurrentValue(), 'sm_', 'jpg');
  }
  
  protected function htmlNav() {
    if (empty($this->options['value'])) return '';
    $deleteBtn = (!empty($this->oForm->options['deleteFileUrl']) and empty($this->options['required'])) ?
      '<a href="'.$this->oForm->options['deleteFileUrl'].'&fieldName='.$this->options['name'].'" class="iconBtn noborder delete confirm" title="Удалить"><i></i></a>' :
      '';
    return
'
<div class="fileNav">
  <div class="fileNavImagePreview">
    '.$deleteBtn.'
    <a href="'.parent::getCurrentValue().'" class="thumb lightbox" title="Текущее изображение"><img src="'.$this->getCurrentValue().'" /></a>
  </div>
</div>
';
  }

}
