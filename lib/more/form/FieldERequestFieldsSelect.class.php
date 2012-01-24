<?php

class FieldERequestFieldsSelect extends FieldESelect {
  
  protected $requiredOptions = array('name', 'action', 'requestedNames');
  
  protected function init() {
    parent::init();
    foreach ($this->options['requestedNames'] as $name) {
      $this->oForm->createElement(array(
        'type' => 'virtual',
        'name' => $name,
        'value' => BracketName::getValue($this->oForm->elementsData, $name)
      ));
    }
  }
  
  public function _js() {
    $jsOpts = Arr::jsObj(Arr::filter_by_keys($this->options, array('url', 'action')));
    return "
$('{$this->oForm->id}').getElements('.type_{$this->options['type']}').each(function(el){
  new Ngn.RequestFieldsSelect(el, $jsOpts);
});
";
  }
  
}
