<?php

class VkVideo {

  static public function getCode(array $itemData) {
    return '<iframe src="http://vkontakte.ru/video_ext.php?oid='.
    $itemData['oid'].'&id='.$itemData['vid'].'&hash='.$itemData['hash2'].'" width="350" height="240" frameborder="0"></iframe>';
  }

}
