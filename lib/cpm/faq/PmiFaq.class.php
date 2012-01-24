<?php

class PmiFaq extends PmiDd {
  
  public $title = 'Вопрос-ответ';
  public $oid = 20;
  
  protected $ddFields = array(
    array(
      'title' => 'Ваше имя',
      'name' => 'name',
      'required' => true
    ),
    array(
      'title' => 'E-mail',
      'name' => 'email',
      'type' => 'email',
      'required' => true
    ),
    array(
      'title' => 'Вопрос',
      'name' => 'question',
      'type' => 'typoTextarea',
      'required' => true
    ),
    array(
      'title' => 'Ответ',
      'name' => 'answer',
      'type' => 'wisiwig',
      'defaultDisallow' => true
    )
  );
  
  protected $behaviorNames = array(
    'feedback'
  );
  
  protected $ddLayoutShow = array(
    'siteItems' => array (
      'name' => 1,
      'question' => 1,
      'answer' => 1,
    ),
    'adminItems' => array (
      'name' => 1,
      'email' => 1,
      'question' => 1,
      'answer' => 1,
    ),
  );
  
  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'completeRedirectType' => 'complete',
        'premoder' => true,
        'showFormOnDefault' => true, 
        'createBtnTitle' => 'Задать вопрос',
        'itemTitle' => 'воспрос',
      )      
    );
  }
  
}
