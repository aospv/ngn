<?php

// @ddLayoutName  eventsInfo
// @ddLayoutTitle Информация о событии

abstract class EventsInfo {

  public $events;
  
  private $data;

  public function getInfo($name, $data, $strName) {
    $name2 = $name.'.'.$strName; // example: createItem.content
    if (! $this->events[$name] and ! $this->events[$name2])
      throw new NgnException("No event info for name '$name' or '$name2' in \$this->events");
    $this->data = $data;
    // Если шаблон не найден
    if ($name == 'createItem') {
      if (empty($strName)) throw new NgnException('$strName can not be empty');
      // Если это сообщение о добавлении, т.е. $name = 'createItem'
      //$this->events[$name2] = $this->events[$name2]
      $info = array(
        'title' => St::dddd(
          isset($this->events[$name2]['title']) ?
            $this->events[$name2]['title'] : 
            $this->events[$name]['title'],
          $data), 
        'text' => $this->getDdTextByItem($strName, $data)
      );
    } else {
      $info = array(
        'title' => St::dddd($this->events[$name]['title'], $data), 
        'text' => St::dddd($this->events[$name]['text'], $data)
      );
    }
    return $info;
  }

  static public function getDdTextByItem($strName, $data) {
    if (!isset($data['itemId']))
      throw new NgnException('$data["itemId"] not defined');
    $itemId = $data['itemId'];
    $oDdoFields = new DdoFields(new DdoSettings($strName), 'eventsInfo');
    $o = new Ddo($data['page'], 'eventsInfo');
    $o->setItem($data);
    $o->elBeginDddd = '`<tr><td><b>`.$title.`</b>:</td><td>`';
    $o->elEnd = '</td></tr>';
    $o->ddddItemsBegin = '`<table>`';
    $o->ddddItemsEnd = '`</table>`';
    return $o->els();
  }

}

