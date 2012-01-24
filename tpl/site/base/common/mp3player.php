<?php

Tt::tpl('common/flash', array(
  'path' => '/i/swf/mp3/player.swf',
  'width' => 200,
  'height' => 20,
  'flashvars' => array(
    'mp3' => str_replace('./', '/', $d['file']),
    'showstop' => 1,
    'showvolume' => 1, 
    'bgcolor1' => '999999',
    'bgcolor2' => 'EDEDED'
  )
));
