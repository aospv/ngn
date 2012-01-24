<?php

class CtrlAdminStat extends CtrlAdmin {

  static public $properties = array(
    'title' => 'Статистика',
    'order' => 310
  );

}

CtrlAdminStat::$properties['onMenu'] = Config::getVarVar('stat', 'enable');
