<?php

class PcpDdItems extends PcpDd {

  public $title = 'Записи';

  public function getProperties() {
    return Arr::append(parent::getProperties(), array(
      array(
        'name' => 'order', 
        'title' => 'Сортировка', 
        'type' => 'ddOrder'
      ),
      array(
        'name' => 'manualOrder',
        'title' => 'включить ручную сортировку',
        'type' => 'bool'
      ),
      array(
        'name' => 'dateField', 
        'title' => 'Поле даты', 
        'type' => 'ddFields'
      ),
      array(
        'name' => 'smResizeType',
        'title' => 'Тип создания превьюшек',
        'type' => 'select',
        'options' => array(
          '' => 'по умолчанию',
          'resize' => 'обрезание',
          'resample' => 'вписывание'
        )
      ),
      // ---------------------------------------
      array(
        'name' => 'futureItems', 
        'title' => 'Записи в будующем', 
        'help' => 'Фильтр по умолчанию. В качестве даты используются следующие 2 поля', 
        'type' => 'bool'
      ),
      array(
        'name' => 'dateFieldBegin', 
        'title' => 'Поле даты начала события', 
        'type' => 'ddDateFields'
      ), 
      array(
        'name' => 'dateFieldEnd', 
        'title' => 'Поле даты окончания события', 
        'type' => 'ddDateFields'
      ),
      // ---------------------------------------        
      array(
        'name' => 'n', 
        'title' => 'Выводить по', 
        'type' => 'select',
        'options' => array(
          '' => 'по умолчанию', 3=>3, 5=>5, 10=>10, 15=>15, 20=>20, 30=>30, 40=>40, 50=>50, 100=>100, 200=>200, 300=>300, 1000=>1000, 9999999 => 'очень много'
        ),
        'default' => 30
      ), 
      array(
        'name' => 'tagField', 
        'title' => 'Поле для выборки по тэгу',
        'type' => 'tagFields'
      ), 
      array(
        'name' => 'userTagField', 
        'title' => 'Поле для выборки по пользовательскову тэгу', 
        'type' => 'tagFields'
      ), 
      array(
        'name' => 'userFilterRequired', 
        'title' => 'Фильтр по пользователю обязателен', 
        'type' => 'bool', 
        'default' => 0
      ), 
      array(
        'name' => 'rssTitleField', 
        'title' => 'Поле заголовка RSS', 
        'type' => 'ddFields'
      ), 
      array(
        'name' => 'rssDescrField', 
        'title' => 'Поле текста RSS', 
        'type' => 'ddFields'
      ), 
      array(
        'name' => 'rssN', 
        'title' => 'Число записей в RSS', 
        'type' => 'num'
      ),
      array(
        'name' => 'itemTitle',
        'title' => 'Название одной записи',
        'type' => 'text'
      ),
      array(
        'name' => 'createBtnTitle', 
        'title' => 'Заголовок кнопки создания записи'
      ),
      array(
        'name' => 'userDataBookmarkTitle',
        'title' => 'Название вкладки в данных пользователя пользователя',
        'type' => 'text'
      ),
      array(
        'name' => 'listSlicesType',
        'title' => 'Тип слайсов в списке записей (перед и после списка)',
        'type' => 'listSlicesType',
      ),
      array(
        'name' => 'forbidItemPage',
        'title' => 'Запретить отображение страницы записи',
        'type' => 'bool',
      ),
      array(
        'name' => 'setItemsOnItem',
        'title' => 'Получать данные для всех записей при открытие страницы одной записи',
        'type' => 'bool'
      ),
      array(
        'name' => 'setItemsOnItemLimit',
        'title' => 'Количество получаеммых записей',
        'type' => 'num',
        'default' => 0
      ),
      array(
        'name' => 'ddItemsLayout',
        'title' => 'Режим отображения записей',
        'type' => 'select',
        'default' => 'details',
        'options' => array(
          'details' => 'Детали',
          'list' => 'Список',
          'tile' => 'Плитка'
        )
      ),
      array(
        'name' => 'showRating',
        'title' => 'Отображать рейтинг',
        'type' => 'bool',
        'default' => false
      ),
      array(
        'name' => 'doNotShowItems', 
        'title' => 'Не отображать записи', 
        'type' => 'bool'
      ), 
      array(
        'name' => 'doNotShowNoItems', 
        'title' => 'Не отображать фразу "нет записей"', 
        'type' => 'bool'
      ), 
      array(
        'name' => 'orderFields', 
        'title' => 'Поля для сортировки',
        'type' => 'ddMultiFields'
      ),
      array(
        'name' => 'showDatePeriodLinks',
        'title' => 'Отображать ссылки периодов (За последние ...)',
        'type' => 'bool',
        'default' => false
      ),
      array(
        'name' => 'mysite',
        'title' => 'Используются в качестве записей для Моего сайта',
        'type' => 'bool',
        'default' => false
      ),
      array(
        'name' => 'oneItemFromUser',
        'title' => 'Только по одной записи от каждого пользователя',
        'type' => 'bool',
        'default' => false
      ),
      array(
        'name' => 'disableItemsCache',
        'title' => 'Выключить кэш записей',
        'type' => 'bool',
        'default' => false
      )
    ));
  }

}