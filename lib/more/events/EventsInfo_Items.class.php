<?php

class EventsInfo_Items extends EventsInfo {
  
  public $events = array(
    'createItem' => array(
      'title' => '`Создание записи`',
    ),
    'createItem.contacts' => array(
      'title' => '`Новое сообщение`',
      'subscriptType' => 'none'
    ),
    'createItem.faq' => array(
      'title' => '`Новый вопрос`',
      'subscriptType' => 'editOnly',
    ),
    // ------------------------------------
    'updateItem' => array(
      'title' => '`Изменение записи`',
      'text' => '`Произошло изменение записи <b>`.($title ? $title : $id).`</b>. 
      Вы можете <a href="`.Tt::getHostPath().`/`.$page[`path`].`/$itemId" target="_blank">посмотреть</a> или 
      <a href="`.Tt::getHostPath().`/`.$page[`path`].`?a=edit&itemId=$itemId" target="_blank">отредактировать</a> эту запись.`'
    ),
    'deleteItem' => array(
      'title' => '`Удаление записи`',
      'text' => '`Произошло удаление записи <b>`.($title ? $title : $id).`</b> в разделе <a href="`.$page[`path`].`">`.$page[`title`].`</a>.`'
    ),
    'activateItem' => array(
      'title' => '`Активация записи`',
      'text' => '`Произошла активация записи <b>`.($title ? $title : $id).`</b> в разделе <a href="`.$page[`path`].`">`.$page[`title`].`</a>. 
      Вы можете <a href="`.Tt::getHostPath().`/`.$page[`path`].`/$itemId" target="_blank">посмотреть</a> 
      или <a href="`.Tt::getHostPath().`/`.$page[`path`].`?a=edit&itemId=$itemId" target="_blank">отредактировать</a> эту запись.`'
    ),
    'moveItem' => array(
      'title' => '`Перемещение записи`',
      'text' => '`Произошло перемещение записи <b>$title</b>. 
      Вы можете <a href="`.Tt::getHostPath().`/`.$page[`path`].`/$itemId" target="_blank">посмотреть</a> 
      или <a href="`.Tt::getHostPath().`/`.$page[`path`].`?a=edit&itemId=$itemId" target="_blank">отредактировать</a> эту запись.`'
    ),
    'deactivateItem' => array(
      'title' => '`Дезактивация записи`',
      'text' => '`Произошла дезактивация записи  <b>`.($title ? $title : $id).`</b> в разделе <a href="`.$page[`path`].`">`.$page[`title`].`</a>. 
      Вы можете <a href="`.Tt::getHostPath().`/`.$page[`path`].`/$itemId" target="_blank">посмотреть</a> 
      или <a href="`.Tt::getHostPath().`/`.$page[`path`].`?a=edit&itemId=$itemId" target="_blank">отредактировать</a> эту запись.`'
    )
  );
  
  public function __construct() {
  	if (!$eventsInfo = Config::getVar('eventsInfo', true)) return;
  	foreach ($eventsInfo as $k => $v) {
  		$this->events[$k] = $v;
  	}
  }
  
}