<?php

class GrabberStructHtmlDated extends GrabberStructBase {
  
  public function getFields() {
    return array_merge(
      parent::getFields(),
      array(
        //'col1' => array('type' => 'col'),
        'urlN' => array(
          'title' => 'Ссылка по счету', 
          'type' => 'select',
          'options' => array(
            0 => 'первая',
            1 => 'вторая',
            2 => 'третья',
            3 => 'третья',
            4 => '4-я',
            5 => '5-я',
            6 => '6-я',
            7 => '7-я',
            8 => '8-я',
            9 => '9-я',
          ),
        ),
        'itemsBegin' => array(
          'title' => 'Кусок текста перед началом <b>списка записей</b>',
          'descr' => 'Кусок текста должен быть уникальным в рамках <b>страницы со списком записей</b>',
          'required' => true
        ), 
        'itemsEnd' => array(
          'title' => 'Кусок текста после конца <b>списка записей</b>',
          'descr' => 'Кусок текста должен быть уникальным в рамках <b>страницы со списком записей</b>',
          'required' => true
        ), 
        'itemContentBegin' => array(
          'title' => 'Кусок текста перед <b>контентом записи</b>',
          'descr' => 'Кусок текста должен быть уникальным в рамках <b>cписка записей</b>',
          'required' => true
        ), 
        'itemContentEnd' => array(
          'title' => 'Кусок текста после <b>контента записи</b>',
          'required' => true
        ),
        //'col2' => array('type' => 'col'),
        'dateMode' => array(
          'title' => 'Откуда брать дату',
          'type' => 'select',
          'options' => array(
            '' => '-',
            'item' => 'Из текста в псписке записей',
            'page' => 'Со страницы',
          ),
          'required' => true
        ),
        'dateTagBegin' => array(
          'title' => 'Кусок текста перед датой',
          'descr' => 'Кусок текста должен быть уникальным в рамках <b>контента записи</b>/<b>страницы записи</b>',
          'required' => true
        ), 
        'dateTagEnd' => array(
          'title' => 'Кусок текста после даты',
          'required' => true
        ),      
        'dateFormat' => array(
          'title' => 'Формат даты',
          'type' => 'select',
          'options' => array(
            '' => '',
            'Y-m-d H:i' => 'ГГГГ-ММ-ДД ЧЧ:ММ',
            'Y-m-d' => 'ГГГГ-ММ-ДД',
            'd.m.Y H:i' => 'ДД.ММ.ГГГГ ЧЧ:ММ',
            'd.m.Y H:i:s' => 'ДД.ММ.ГГГГ ЧЧ:ММ:СС',
            'd.m.Y' => 'ДД.ММ.ГГГГ',
            'd.m H:i' => 'ДД.ММ ЧЧ:ММ',
            'd ru-month Y' => 'ДД месяц ГГГГ',
            'd ru-month Y H:i' => 'ДД месяц ГГГГ ЧЧ:ММ',
          ),
          'required' => true
        ),
        //'col3' => array('type' => 'col'),
        'garbage' => array(
          'title' => 'Мусор в <b>списке записей</b>', 
          'type' => 'fieldSet', 
          //'type' => 'text', 
          'fields' => array(
            array(
              'title' => 'Начало куска',
              'name' => 'begin'
            ), 
            array(
              'title' => 'Конец куска',
              'name' => 'end'
            )
          )
        ),
        'titleMode' => array(
          'title' => 'Откуда брать заголовок',
          'type' => 'select',
          'options' => array(
            '' => '-',
            'link' => 'Из ссылки в <b>списке записей</b>',
            'page' => 'Из тэга title <b>страницы записи</b>',
          ),
          'required' => true
        ),
        'pageTitle' => array(
          'title' => 'Заголовок страницы',
          'type' => 'header'
        ),
        'pageTitleDelimiter' => array(
          'title' => 'Разделитель заголовка',
        ),
        'pageTitleFormat' => array(
          'title' => 'Формат заголовка страницы',
          'type' => 'select',
          'options' => array(
            1 => '[Заголовок] [Разделитель] [...]',
            2 => '[...] [Разделитель] [Заголовок]',
          )
        ),
        'content' => array(
          'title' => 'Запись',
          'type' => 'header'
        ),
        'contentBegin' => array(
          'title' => 'Кусок текст перед началом контента на <b>странице записи</b>',
          'descr' => 'Кусок текста должен быть уникальным в рамках <b>страницы записи</b>',
          'required' => true
        ), 
        'contentEnd' => array(
          'title' => 'Текст после контента',
          'required' => true
        ), 
        'garbage2' => array(
          'title' => 'Мусор в <b>контенте</b>', 
          'type' => 'fieldSet',
          'fields' => array(
            array(
              'title' => 'Начало куска',
              'name' => 'begin'
            ), 
            array(
              'title' => 'Конец куска',
              'name' => 'end'
            )
          )
        )
      )
    );
  }

  public function getVisibilityConditions() {
    return array_merge(
      parent::getVisibilityConditions(),
      array(
        array('pageTitle', 'titleMode', 'v == "page"')
      )
    );
  }
  
}

