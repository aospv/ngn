<?php

class PcpUserData extends Pcp {
  
  public $title = 'Данные пользователя';
  
  public function getProperties() {
    return Arr::append(parent::getProperties(), array(
      array(
        'name' => 'wallEnable',
        'title' => 'Стена включена',
        'type' => 'bool'
      ),
      array(
        'name' => 'wallTitle',
        'title' => 'Название вкладки стены',
        'type' => 'text'
      ),      
      array(
        'name' => 'commentsEnable',
        'title' => 'Комментарии включены',
        'type' => 'bool'
      ),
      array(
        'name' => 'answersEnable',
        'title' => 'Ответы включены',
        'type' => 'bool'
      ),
      array(
        'name' => 'profilePageIds',
        'title' => 'Используемые разделы профилей',
        'type' => 'myProfilePagesMultiselect',
        'required' => 1
      ),
      array(
        'name' => 'ddItemsPageIds',
        'title' => 'Используемые разделы с записями',
        'type' => 'itemsPagesMultiselect'
      ),
      array(
        'name' => 'userItemsLimit',
        'title' => 'Лимит записей',
        'type' => 'num'
      ),
      array(
        'name' => 'allowEmail',
        'title' => 'Разрешить отправку e-mail\'ов',
        'type' => 'bool',
        'default' => true
      ),
      array(
        'name' => 'allowAnonimEmail',
        'title' => 'Разрешить отправку e-mail\'ов от анонимных пользователей',
        'type' => 'bool',
        'default' => true
      ),
      array(
        'name' => 'showRegDate',
        'title' => 'Отображать дату регистрации',
        'type' => 'bool',
        'default' => true
      )
    ));
  }
  
}