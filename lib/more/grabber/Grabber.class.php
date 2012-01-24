<?php

class Grabber {

  /**
   * Возвращает источник для граббинга из него
   * 
   * @param   integer   ID канала
   * @return  GrabberSourceAbstract
   */
  static public function getSource($id) {
    if (!($curData = db()->selectRow('SELECT * FROM grabberChannel WHERE id=?d', $id)))
      throw new NgnException('No channel ID='.$id, 1001);
    return O::get('GrabberSource'.ucfirst($curData['type']), $id);
  }
  
  static public function import() {
    $n = 0;
    foreach (self::getActualChannelIdsForImport() as $id) {
      try {
        $n += count(self::get($id)->import());
      } catch (NgnException $e) {
        // Логируем ошибку
        LogWriter::html('grabberErrors', $e->getMessage());
        // Отправляем сообщение об ошибке админу
        $user = DbModelCore::get('users', Config::getVarVar('grabber', 'admin'));
        $o = new SendEmail();
        $o->send($user['email'], SITE_TITLE.': Grabber error', $e->getMessage());
        // Если ошибок больше, чем дозволено, отключаем канал и обнуляем счетчик
        $attemptsN = db()->selectCell('SELECT attempts FROM grabberChannel WHERE id=?d', $id);
        if ($attemptsN >= Config::getVarVar('grabber', 'attemptsBeforeDisactivate')) {
          db()->query('UPDATE grabberChannel SET active=0, attempts=0 WHERE id=?d', $id);
        }
        // Инкриментируем счетчик попыток
        db()->query('UPDATE grabberChannel SET attempts=attempts+1 WHERE id=?d', $id);
      }
    }
    return $n;
  }
  
  static protected function getActualChannelIdsForImport() {
    return db()->selectCol('
      SELECT id FROM grabberChannel
      WHERE active=1 AND dateLastGrab < ?
      ORDER BY oid',
      date('Y-m-d H:i:s', time()-Config::getVarVar('grabber', 'period')));
  }
  
  static public function getTypes() {
    return ClassCore::getDescendants('GrabberSourceAbstract');
  }
  
  static public function getTitle($type) {
    return ClassCore::getStaticPropertyByType('GrabberSource', $type, 'title');
  }
  
  /**
   * Возвращает объект граббера
   *
   * @param   string  Тип граббера
   * @param   integer ID канала
   * @return  GrabberSourceAbstract
   */
  static public function getGrabber($type, $channelId) {
    return O::get('Grabber'.ucfirst($type), $channelId);
  }
  
  /**
   * @param   string  Тип граббера
   * @return  GrabberStructBase
   */
  static public function getStruct($type) {
    $class = 'GrabberStruct'.ucfirst($type);
    return Lib::exists($class) ? O::get($class) : O::get('GrabberStructBase');
  }
  
}