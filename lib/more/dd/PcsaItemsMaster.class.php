<?php

class PcsaItemsMaster extends Pcsa {
  
  public function action($initSettings) {
    if (empty($initSettings['slavePageId'])) return;
    DbModelPages::addSettings(
      $initSettings['slavePageId'],
      Arr::filter_by_keys($initSettings, array(
        'mysite', 'ownerMode'
      ))
    );
    return $initSettings;
  }

}