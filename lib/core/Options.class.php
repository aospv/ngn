<?php

abstract class Options {
  
  protected $requiredOptions = array();
  public $options = array();
  protected $optionsDefined = false;
  
  public function setOptions(array $options) {
    if ($this->optionsDefined) return;
    $this->optionsDefined = true;
    $this->options = array_merge($this->options, $options);
    //prr(get_class($this));
    //if (get_class($this) == 'StoreOrderForm') die2(22);
    //pr($this->requiredOptions);
    foreach ($this->requiredOptions as $k)
      if (!isset($this->options[$k]))
        throw new NgnException('Class "'.get_class($this).'": option "'.$k.'" does not exists');
  }

}
