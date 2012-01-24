<?php

/**
 * Класс служит для получения текста текста рассылки
 *
 */
class Notify_SubscribeText {
  
  private function get($name) {
    if (!(Lib::exists('Notify_SubscribeText_'.$name))) return false;
    return O::get('Notify_SubscribeText_'.$name);
  }
  
  /**
   * Возвращает текст рассылки
   *
   * @param   integer   ID пользователя
   * @param   string    Тип
   * @param   string    Время последней отправки сообщения в формате 'Y-m-d H:i:s'
   */
  public function getText($userId, $type, $lastSendTime) {
    if (!$type) throw new NgnException('$type not defined');
    if (!strstr($type, '_')) throw new NgnException('"_" not exists in type "'.$type.'"');
    list($typeName, $typeMethod) = explode('_', $type);
    if (!$typeMethod) { 
      throw new NgnException(
        'Method not defined. Error in type syntax of "'.$type.'". It must contains "_"');
    }
    if (!($obj = $this->get($typeName))) return false;
    $m = 'getData_'.$typeMethod;
    if (!method_exists($obj, $m)) return false;
    if (!($data = $obj->$m($userId, $lastSendTime))) return false;
    $m2 = 'getTpl_'.$typeMethod;
    return method_exists($obj, $m2) ?
      $obj->$m2($data) :
      $this->getHtml($type, $data);
  }
  
  private function getHtml($type, &$data) {
    return Tt::getTpl('notify/msgs/'.$type, $data);
  }
  
}
