<?php

class FieldEPageId extends FieldEHiddenWithRow {

  public function _js() {
    $json = Arr::jsObj(empty($this->options['dd']) ? array() : array('dd' => true));
    return "
$('{$this->oForm->id}').getElements('.type_pageId').each(function(el){
  new Ngn.frm.Page.Id(el, $json);
});
";
  }

}