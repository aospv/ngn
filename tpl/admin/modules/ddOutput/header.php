<?php

$links[] = array(
  'title' => 'Редактирование полей',
  'class' => 'list',
  'link' => Tt::getPath(1).'/ddField/'.$d['page']['strName'],
);

Tt::tpl('admin/common/module-header', array('links' => $links));
