<?php

class GrabberStructRss extends GrabberStructBase {
  
  public function getFields() {
    return array_merge(
      parent::getFields(),
      array(
        'url' => array(
          'title' => 'Ссылка на rss-канал', 
          'type' => 'rssUrl',
          'required' => true
        ), 
        'itemsLimit' => array(
          'title' => 'Количество записей для добавления при импортировании', 
          'type' => 'num',
          'default' => 3,
          'required' => true
        ), 
        'contentBegin' => array(
          'title' => 'Начало контента'
        ), 
        'contentEnd' => array(
          'title' => 'Конец контента'
        ), 
        'garbage' => array(
          'title' => 'Мусор', 
          'type' => 'fieldSet', 
          'fields' => array(
            array(
              'name' => 'begin',
              'title' => 'Начало куска'
            ), 
            array(
              'name' => 'end',
              'title' => 'Конец куска'
            )
          )
        ),
        /*
        'subjects' => array(
          'title' => 'Тематики', 
          'type' => 'fieldSet', 
          'fields' => array(
            array(
              'title' => 'Название',
              'name' => 'value'
            ),
          )
        )
        */
      )
    );
  }
  
}