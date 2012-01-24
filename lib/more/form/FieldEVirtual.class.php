<?php

/**
 * Этот элемент используется в том случае, если необходимо полчить значение из
 * POST'а, но при этом не создавать HTML-элемент
 */
class FieldEVirtual extends FieldEAbstract {

  public $options = array(
    'noRowHtml' => true
  );

}