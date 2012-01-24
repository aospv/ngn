<?php 

Tt::tpl('dd/css', $d);

/* @var $oDdo Ddo */
$d['oDdo'] = $oDdo = O::get('DdoAdminFactory', $d['page'])->get()
  ->setPagePath(Tt::getPath(4))->setItems($d['items']);

$ddItemsLayout = isset($d['page']['settings']['ddItemsLayout']) ?
  $d['page']['settings']['ddItemsLayout'] : 'details';
Tt::tpl('admin/modules/pages/items/'.$ddItemsLayout, $d);
