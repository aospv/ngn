<?php

class SoundStat {
  
  /**
   * Всё суммарное время прослушивания трека
   *
   * @param unknown_type $strName
   * @param unknown_type $itemId
   */
  static function getTotalTrackTime($strName, $itemId) {
    return db()->selectCell('
      SELECT SUM(sec) FROM sound_play_time_log
      WHERE strName=? AND itemId=?d', $strName, $itemId);
  }
  
  /**
   * Всё суммарное время нескольких треков
   *
   * @param unknown_type $itemIds
   */
  static function getTotalTracksTime($strName, $itemIds) {
    if (!is_array($itemIds))
      throw new NgnException('$itemIds must be an array');
    if (empty($itemIds)) return array();
    return db()->select('
      SELECT itemId AS ARRAY_KEY, SUM(sec) AS sec FROM sound_play_time_log
      WHERE strName=? AND itemId IN (?a) GROUP BY itemId',
    $strName, $itemIds);
  }
  
}
