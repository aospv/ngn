<?php

/* @var $oDdo Ddo */
$oDdo = $d['o'];
$extraClasses = array();
if (($itemClasses = Config::getVarVar('dd', 'useFieldNameAsItemClass', true))) {
  foreach ($itemClasses as $v) {
    $extraClasses[] = $v['field'].'_'.Ddo::getFlatValue($d[$v['field']]);
  }
}

print '<div class="item'.
  ($d['active'] ? '' : ' nonActive').
  (!empty($d['image']) ? ' isImage' : '').
  ($extraClasses ? ' '.implode(' ', $extraClasses) : '').
  '" data-id="'.$d['id'].'" data-userId="'.$d['userId'].'">';
print '<div class="itemBody">';

$canEdit = (!empty($d['priv']['edit']) or !empty($d['canEdit']));

if ($canEdit) {
  if ($d['premoder']) Tt::tpl('editBlocks/premoderBlock', $d);
  else Tt::tpl('editBlocks/editBlock', $d);
}

$fields = array_values($oDdo->fields);
for ($n=0; $n<count($fields); $n++) {
  $f =& $fields[$n];
  // Открывающийся тэг группы
  if ($n == 0 or DdFieldCore::isGroup($f['type'])) {
    // Если это первый элемент или это элемент после Заголовока
    print '<!-- Open fields group --><div class="hgrp hgrpt_'.$f['type'].' hgrp_'.$f['name'].'">'; 
  }
  $typeData = DdFieldCore::getTypeData($f['type'], false);
  if (empty($typeData['noElementTag'])) {
    $el = $d[$f['name']]; // $el содержит текущее значение элемента записи
    print St::dddd($oDdo->elBeginDddd, $f);
    print $oDdo->el($el, $f['name'], $d['id']);
    print $oDdo->elEnd;
  }
  // Закрывающийся тэг группы
  if (
  isset($fields[$n + 1]) and 
  DdFieldCore::isGroup($fields[$n+1]['type'])
  ) {
    // Если это последний элемент или элемент перед Заголовком
    print '</div><!-- Close fields group -->';
  }
}

// Закрывающийся тэг группы
print '</div><!-- Close fields group -->';

print '<div class="clear"><!-- --></div>';
print '</div>';
print '</div>';

