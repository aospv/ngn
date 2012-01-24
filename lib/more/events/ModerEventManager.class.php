<?php

class ModerEventManager {
  
  /**
   * Производит действия связанные с заданным событием для заданного раздела
   *
   * @param   integer   ID раздела
   * @par am   integer   ID пользователя, совершившего событие
   * @param   string    Имя события
   * @param   array     Данные события
   */
  static public function event($pageId, $userId, $name, $data) {
    if (!$pageId) throw new NgnException('$pageId not defined');
    /* @var $oPages Pages */
    $oPages = O::get('Pages');
    if (!($page = DbModelCore::get('pages', $pageId)) === false) return;
    $data['page'] = $page;
    $oEventsInfo = new EventsInfo_Items();
    $event = $oEventsInfo->getInfo($name, $data, $page['strName']);

    
    
    $oSender = new Notify_SenderRobot();
    // Отправляем владельцу записи, если это не он совершил событие
    //if ($data['userId'] != $userId) {
    //  $oSender->sendIfSubscribed(
    //    'event_'.$name, $data['userId'], $event['title'], $event['text']);
    //}
    // Рассылаем уведомления модераторам этого раздела
    $recipientModerIds = Moder::getModerIds($pageId);
    $forceModerSubscribe = Config::getVarVar('event', 'forceModerSubscribe');
    foreach ($recipientModerIds as $id) {
      $forceModerSubscribe ?
        $oSender->send($id, $event['title'], $event['text']) :
        $oSender->sendIfSubscribed('event_'.$name, $id, $event['title'], $event['text']);
    }
  }
  
}

