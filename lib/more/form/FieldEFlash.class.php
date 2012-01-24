<?php

class FieldEFlash extends FieldEFile {

  protected $allowedMimes = array('application/x-shockwave-flash');
  
  /**


    $path = (!empty($data[$k]['file']) and empty($data[$k]['file']['error'])) ?
      $this->afterUpdateFile($itemId, $data[$k]['file'], $k) :
      $this->beforeUpdateData[$k]['path'];
    $this->oItems->update(
      $itemId,
      array(
        $k => array(
          'path' => $path,
          'w' => $data[$k]['w'],
          'h' => $data[$k]['h']
        )
      )
    );


   */

}
