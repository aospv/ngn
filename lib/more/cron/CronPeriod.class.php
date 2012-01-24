<?php

class CronPeriod {
  
  /**
   * Возвращает все существующие в платформе крон-периоды
   *
   * @return array
   */
  static public function getPeriods() {
    return array(
      'every5min' => array(
        'title' => 'раз в 5 минут',
        'min' => 5
      ),
      'every10min' => array(
        'title' => 'раз в 10 минут',
        'min' => 10
      ),
      'every30min' => array(
        'title' => 'раз в 30 минут',
        'min' => 30
      ),
      'every1h' => array(
        'title' => 'раз в час',
        'min' => 60
      ),
      'every2h' => array(
        'title' => 'раз в 2 часа',
        'min' => 120
      ),
      'every3h' => array(
        'title' => 'раз в 3 часа',
        'min' => 180
      ),
      'every6h' => array(
        'title' => 'раз в 6 часов',
        'min' => 360
      ),
      'every12h' => array(
        'title' => 'раз в 12 часов',
        'min' => 720
      ),
      'daily' => array(
        'title' => 'раз в сутки',
        'min' => 1440
      ),
      'every3d' => array(
        'title' => 'раз в сутки',
        'min' => 1440
      ),
      'weekly' => array(
        'title' => 'раз в неделю',
        'min' => 1440
      ),
      'every2w' => array(
        'title' => 'раз в неделю',
        'min' => 1440
      ),
      'monthly' => array(
        'title' => 'раз в неделю',
        'min' => 1440
      ),
    );
  }
  
}
