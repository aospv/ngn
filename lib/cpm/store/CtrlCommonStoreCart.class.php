<?php

class CtrlCommonStoreCart extends CtrlMapper {
  
  public function getMappingObject() {
    return StoreCart::get();
  }
  
}
