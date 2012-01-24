<?php

class Notify_SubscribeText_items {
  
  function getData_new($userId, $lastSendTime) {
    $data = array();
    foreach (Notify_SubscribePages::getSubscribedItems($userId, 'items_new') as $v) {
      $page = DbModelCore::get('pages', $v['pageId']);
      $oDdItems = O::get('DdItems', $page['id']);
      $oDdItems->setOrder('datePublish DESC');
      $oDdItems->addRangeFilter(
        'datePublish', $lastSendTime, date('Y-m-d H:i:s', time()+99999));
      if (!($items = $oDdItems->getItems())) continue;
      $data[$v['pageId']]['items'] = $items;
      $data[$v['pageId']]['data'] = $page;
    }
    return $data;
  }
  
  function getTpl_new($data) {
    $html = '';
    foreach ($data as $page) {      
      /* @var $oDdoFields DdoFields */
      $oDdoFields = O::get('DdoFields', $page['data']['strName'], 'eventsInfo');
      $oDdoFields->getSystem = false;
      $fields = $oDdoFields->getFields();
      
      /* @var $oDdo Ddo */
      $oDdo = O::get(
        'Ddo', $page['data']['strName'], $page['data']['path'])->
          setItems($page['items']);
      $oDdo->setFields($fields);
      $html .= Tt::getTpl('notify/msgs/items_new', array(
        'oDdo' => $oDdo,
        'page' => $page
      ));
    }
    return $html;
  }
  
}