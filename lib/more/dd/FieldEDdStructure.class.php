<?php

class FieldEDdStructure extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array('' => '— '.LANG_NOTHING_SELECTED.' —');
    $oI = new DbItems('dd_structures');
    foreach ($oI->getItems() as $v)
      $this->options['options'][$v['name']] = $v['title'].' ('.$v['name'].')';
  }
  
  public function _js() {
    return "
var eStrSelect = $('strNamei');
if (eStrSelect) {
  eStrSelect.setStyles({
    'float': 'left',
    'margin-right': '5px'
  });
  var eCont = '<div><a href=\"#\" class=\"iconBtn ddStructure tooltip\" title=\"Редактировать структуру\"><i></i></a><div class=\"clear\"><!-- --></div></div>'.toDOM()[0].inject(eStrSelect, 'after');
  var eEditStrBtn = eCont.getElement('.iconBtn');
  eEditStrBtn.addEvent('click', function(e){
    var strName = eStrSelect.get('value');
    if (strName) window.open(Ngn.getPath(1) + '/ddField/' + strName, '_blank');
    else alert('Структура не задана');
    return false;
  });
}
";
  }

}