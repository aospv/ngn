<?php

class FieldEPageLink extends FieldEText {

  public function _js() {
    return "
$('{$this->oForm->id}').getElements('.type_pageLink').each(function(el){
  new Ngn.frm.Page.Link(el);
});
";
  }

}