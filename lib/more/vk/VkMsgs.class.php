<?php

class VkMsgs extends VkAuthBase {

  public function getSentUserIds() {
    return FileList::get($this->auth->userDataFolder.'/sentUsers');
  }

  public function send(array $data) {
    Arr::checkEmpty($data, array('message', 'to_id'));
    //$data['to_id'] = xxx;
    if (in_array($data['to_id'], $this->getSentUserIds())) {
      LogWriter::str('vkMsgs', $data['to_id'].' already sent');
      return;
    }
    if (!$this->auth->initChas($data['to_id'])) return;
    $data['chas'] = $this->auth->chas;
    $c = $this->auth->curl->post('http://m.vkontakte.ru/mailsent?pda=1', $data);
    FileList::addItem($this->auth->userDataFolder.'/sentUsers', $data['to_id']);
    LogWriter::str('vkMsgs', 'send to '.$data['to_id']);
    if (strstr($c, 'Вы попытались загрузить более')) {
      $data2 = $data;
      $data2['act'] = 'a_send';
      //$c2 = $this->auth->curl->post('http://vkontakte.ru/al_mail.php', $data2);
      file_put_contents($auth->dataFolder.'/send2_res_'.$data['to_id'].'_'.date('H-i-s'), $c2);
      throw new NgnException('Необходимо подождать 15 минут до следующей отправки сообщения', 324234);
      return;
    }
    file_put_contents($this->auth->userDataFolder.'/send_res_'.$data['to_id'].'_'.date('H-i-s'), $c);
    if (!strstr($c, '200 OK'))
      throw new NgnException("=( {$data['to_id']}", 123);
  }
  
  public function getLastSentUserId() {
    $sentIds = $this->getSentUserIds();
    if (!$sentIds) return false;
    $ids = O::get('VkFriends', $this->auth)->getFriends();
    $index = array_search($sentIds[count($sentIds)-1], $ids);
    return $ids[$index];
  }

}
