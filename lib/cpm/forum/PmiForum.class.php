<?php

class PmiForum extends PmiDd {
  
  public $title = 'Форум';
  public $oid = 70;
  
  protected $behaviorNames = array(
    'comments'
  );
  
  protected $ddFields = array(
    array(
      'title' => 'Тема',
      'name' => 'title',
      'type' => 'typoText',
      'required' => true
    ),
    array(
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'typoTextarea',
      'required' => true
    ),
    array(
      'title' => 'Форум',
      'name' => 'forum',
      'type' => 'ddTagsSelect',
      'required' => true
    )
  );
  
  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'tagField' => 'forum',
        'itemTitle' => 'тема',
        'comments' => true
      )
    );
  }
  
  protected function afterCreate($node) {
    parent::afterCreate($node);
    $o = new DdTagsTagsFlat(new DdTagsGroup('forum', 'forum'));
    $o->create('Общий');
    $o->create('Флудильня');
    $o->create('Работа сайта');
  }
  
  protected $pageBlocks = array(
    array(
      'type' => 'tags',
      'params' => array(
        'colN' => 3
      ),
      'settings' => array(
        'tagField' => 'forum',
        'showNullCountTags' => true,
      )
    )
  );
  
}
