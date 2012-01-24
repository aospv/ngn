<?php

abstract class EventAbstract {

  public function html(array $data);

}

class DdEvent extends EventAbstract {

  public function html(array $data) {
    return 
      O::get('Ddo', Pages::getNode_s($data['pageId'], 'events'))->setItem(
        O::get('DdItemsGetter', $data['strName'])->getItemF($data['id'])
      )->els();
  }
  
}