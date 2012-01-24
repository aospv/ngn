<?

class FieldEPixels extends FieldEText {

  protected function init() {
     parent::init();
     $this->options['value'] = (int)$this->options['value'].'px';
  }
  
  public function isEmpty() {
    if (!isset($this->options['value'])) return true;
    return (int)$this->options['value'] == 0;
  }

}