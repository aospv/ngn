<?php

class MainTplSettingsFields extends Fields {
  
  public function __construct($tplName) {
    parent::__construct(
      Arr::append(
        //MainTplSettings::getFields($tplName),
        array(
          array(
            'title' => 'Лейаут',
            'type' => 'radio',
            'options' => array(
              1 => '<img src="/i/img/layout/1.gif" />',
              2 => '<img src="/i/img/layout/2.gif" />',
              3 => '<img src="/i/img/layout/3.gif" />',
              4 => '<img src="/i/img/layout/4.gif" />',
              5 => '<img src="/i/img/layout/5.gif" />',
              6 => '<img src="/i/img/layout/6.gif" />',
              7 => '<img src="/i/img/layout/7.gif" />',
            )
          )
        ),
        array(
          array(
            'title' => 'Восстановить параметры по умолчанию',
            'type' => 'button',
            'name' => 'delete'
          )
        )
      )
    );
  }
  
}