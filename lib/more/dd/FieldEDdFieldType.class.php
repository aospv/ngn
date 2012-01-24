<?php

class FieldEDdFieldType extends FieldESelect {

  protected function init() {
    $this->options['options'] = Arr::get(DdFieldCore::getTypes(), 'title', 'KEY');
    parent::init();
  }
  
  public function _html() {
    $s = '<table cellpadding="0" cellspacing="0" id="itemsTable">';
    $checked = empty($this->options['value']) ?
      Arr::first_key($this->options['options']) : $this->options['value'];
    foreach ($this->options['options'] as $k => $v) {
      $s .=
        '<tr>'.
        '<td><input type="radio" name="type" value="'.$k.'"'.
        ($checked == $k ? ' checked' : '').' />'.
        "\n<script type=\"text/javascript\">Ngn.cp.ddFieldType.types.$k = ".
          Arr::jsObj(DdFieldCore::getTypeData($k))."</script>\n".
        '</td>'.
        '<td><img src="'.DdFieldCore::getIconPath($k).'" title="'.$k.'" /></td>'.
        '<td>'.$v.'</td>'.
        '</tr>';
    }
    $s .= '</table>';
    return $s;
  }
  
  public function _js() {
    return "
new Ngn.cp.ddFieldType.Properties($('{$this->oForm->id}'), '{$this->options['name']}');
";
  }

}