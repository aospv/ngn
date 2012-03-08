<?php

interface ProcessDynamicPageBlock {

  /**
   * @param array Array of DbModelPageBlocks
   */
  public function processDynamicBlockModels(array &$blockModels);

}
