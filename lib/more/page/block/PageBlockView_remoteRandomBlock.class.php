<?php

class PageBlockView_remoteRandomBlock extends PageBlockViewAbstract {

  public function html() {
    $projectNames = array(
      'litcult.ru' => array('blockId' => 92),
      'baby-nn.ru' => array('blockId' => 67),
      //'imhonn.ru' => array('blockId' => 67),
      'mctb.ru' => array('blockId' => 43),
      'of.nnov.ru' => array('blockId' => 14)
    );
    $p = $projectNames[array_rand($projectNames)];
    $p['blockId'];
    return 1;
  }

}
