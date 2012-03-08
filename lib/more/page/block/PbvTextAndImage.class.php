<?php

class PbvTextAndImage extends PbvAbstract {

  protected $extendImageData = true;
  
  protected function init() {
    SFLM::addCssLib(SFLM::DYNAMIC_LIB_NAME, 's2/css/common/pageBlocks.css?blockType=textAndImage');
  }

}
