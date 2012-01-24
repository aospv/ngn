<?php

DdFieldCore::registerType('ddTagsConsecutiveSelect', array(
  'dbType' => 'VARCHAR',
  'dbLength' => 255,
  'title' => 'Последовательный выбор тэга',
  'order' => 260,
  'tags' => true,
  'tagsTree' => true
));

class FieldEDdTagsConsecutiveSelect extends FieldEText {

  /**
   * @var DdTagsTagsBase
   */
  protected $oTags;
  
  protected function init() {
    parent::init();
    $this->oTags = DdTags::get($this->oForm->strName, $this->options['name']);
  }

  protected function preparePostValue() {
    $this->options['value'] = $this->oTags->getParentIds2($this->options['value']);
  }

  public function _html() {
    $d = array('name' => $this->options['name']);
    if (!empty($this->options['value'])) {
      $parentId = 0;
      for ($i=0; $i<count($this->options['value']); $i++) {
        $tagId = $this->options['value'][$i];
        $d['items'][$i] = array(
          'default' => $tagId,
          'options' => array('' => '—') + Arr::get($this->oTags->getTags($parentId), 'title', 'id')
        );
        $parentId = $tagId;
      }
    } else {
      $d['items'][0] =
        array('options' => array('' => '—') + Arr::get($this->oTags->getTags(0), 'title', 'id'));
    }
    return Tt::getTpl('dd/consecutiveSelect', $d);
  }
  
  public function _js() {
    return "
$('{$this->oForm->id}').getElements('.type_{$this->type}').each(function(el){
  new Ngn.frm.ConsecutiveSelect(el, '{$this->oForm->strName}');
});
";
  }

} 
