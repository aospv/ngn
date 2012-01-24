<?php

class PmiBehaviorSliceAddress extends PmiBehaviorAbstract {

  public function action($pageId, $node) {
    // Текстовый слайс адреса
    Slice::replace(array(
      'id' => 'address_'.$pageId,
      'pageId' => $pageId,
      'title' => 'Адрес',
      'type' => 'text'
    ));
  }  

}