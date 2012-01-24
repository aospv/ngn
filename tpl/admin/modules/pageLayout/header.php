<?php

$links = array(
  array(
    'title' => 'Формат страницы <b>по умолчанию</b>',
    'link' => Tt::getPath(2),
    'class' => 'layout'
  )
);

if (!empty($d['layoutPages'])) {
  foreach ($d['layoutPages'] as $v) {
    $links[] = array(
      'title' => 'Формат страницы <b>'.$v['title'].'</b>',
      'link' => Tt::getPath(2).'/'.$v['id'],
      'class' => 'layout'
    );
  }
}

Tt::tpl('admin/common/module-header', array(
  'links' => $links
));
