<?php

class FieldEPageOwnerMode extends FieldESelect {

  protected function defineOptions() {
    $this->options['title'] = 'Режим владельца страницы';
    $this->options['options'] = array(
      '' => 'по умолчанию',
      'author' => 'автор записи'
    );
    if (Config::getVarVar('userGroup', 'enable'))
      $this->options['options']['userGroup'] = 'сообщество';
  }

}

abstract class PcpDd extends Pcp {

  public $editebleContent = true;
  
  public function getProperties() {
    return Arr::append(parent::getProperties(), array(
      array(
        'name' => 'strName', 
        'title' => 'Структура', 
        'type' => 'ddStructure', 
        'maxlength' => 50, 
        'required' => 1
      ), 
      array(
        'name' => 'tplName', 
        'title' => 'Имя каталога с шаблонами', 
        'help' => 'оставить пустым, если используются стандартные шаблоны структуры', 
        'type' => 'text'
      ), 
      array(
        'name' => 'formTpl', 
        'title' => 'Имя шаблона формы', 
        'type' => 'text'
      ), 
      array(
        'name' => 'premoder', 
        'title' => 'Премодерация', 
        'type' => 'bool'
      ), 
      array(
        'name' => 'comments', 
        'title' => 'Комментарии', 
        'type' => 'bool'
      ), 
      array(
        'name' => 'allowAnonym', 
        'title' => 'Разрешить анонимные комментарии', 
        'type' => 'bool', 
        'help' => 'Используется только в том случае, если комментарии включены'
      ), 
      array(
        'name' => 'smW', 
        'title' => 'Ширина превьюшки', 
        'type' => 'num'
      ), 
      array(
        'name' => 'smH', 
        'title' => 'Высота превьюшки', 
        'type' => 'num'
      ), 
      array(
        'name' => 'mdW', 
        'title' => 'Ширина уменьшенной копии', 
        'type' => 'num'
      ), 
      array(
        'name' => 'mdH', 
        'title' => 'Высота уменьшенной копии', 
        'type' => 'num'
      ), 
      array(
        'name' => 'showFormOnDefault', 
        'title' => 'Показывать форму по умолчанию', 
        'type' => 'bool'
      ), 
      array(
        'name' => 'completeRedirectType', 
        'title' => 'Что делать после создания/удаления/изменения/и т.п. записи', 
        'help' => 'Перебрасывать на страницу "page.path/complete" после добавления новой записи. Иначе перенаправляется на реферер', 
        'type' => 'select', 
        'options' => array(
          'self' => 'На себя саму', 
          'referer' => 'Редирект на реферер', 
          'referer_item' => 'Редирект на реферер или запись (для "edit" и "new")', 
          'complete' => 'Редирект на страницу "complete"'
        )
      ), 
      array(
        'name' => 'editTime', 
        'title' => 'Время редактирования', 
        'decsr' => 'Время допущенное для редактирования пользователем записи относительно времени её создания. (в секундах)', 
        'type' => 'select', 
        'options' => array(
          0 => 'не определено',
          60 => '1 минута', 
          60*3 => '3 минуты', 
          60*5 => '5 минут', 
          60*10 => '10 минут',
          60*20 => '20 минут',
          60*30 => '30 минут',
          60*60 => '1 час',
          60*60*2 => '2 часа',
          60*60*3 => '3 часа',
          60*60*6 => '6 часов',
          60*60*12 => '12 часов',
          60*60*24 => 'сутки',
          60*60*24*2 => '2 суток',
          60*60*24*3 => '3 суток',
          60*60*24*7 => 'неделя',
          60*60*24*30 => 'месяц',
          60*60*24*30*12 => 'год',
          9999999999 => 'очень много',
        )
      ), 
      array(
        'name' => 'titleField', 
        'title' => 'Поле которое используется в качестве заголовка для страницы записи', 
        'type' => 'ddFields'
      ), 
      array(
        'name' => 'titleField', 
        'title' => 'Поле которое используется в качестве заголовка для страницы записи', 
        'type' => 'ddFields'
      ), 
      array(
        'name' => 'ownerMode',
        'type' => 'pageOwnerMode'
      ),
      array(
        'name' => 'myProfileTitle', 
        'title' => 'Заголовок в профиле'
      )
    ));
  }
  
  public function getAfterSaveDialogs(PageControllerSettingsForm $oF) {
    $dialogs = array();
    $a = function($prefix) use (&$dialogs, $oF) {
      $data = $oF->getData();
      if (empty($oF->defaultData[$prefix.'W'])) return;
      if (empty($data[$prefix.'W'])) return;
      if (empty($oF->defaultData[$prefix.'H'])) return;
      if (empty($data[$prefix.'H'])) return;
      if (
      $oF->defaultData[$prefix.'W'] != $data[$prefix.'W'] or 
      $oF->defaultData[$prefix.'H'] != $data[$prefix.'H']) {
        $dialogs[] = array(
          'cls' => 'Ngn.PartialJob.Dialog',
          'options' => array(
            'pjOptions' => array(
              'url' => Tt::getPath(1).'/ddImages/'.O::get('Req')->params[2].
                '?a=json_resize'.ucfirst($prefix).'Images',
              'requestParams' => array(
                'w' => $data[$prefix.'W'],
                'h' => $data[$prefix.'H']
              )
            )
          )
        );
      }
    };
    $a('sm');
    $a('md');
    return empty($dialogs) ? false : $dialogs;
  }

}