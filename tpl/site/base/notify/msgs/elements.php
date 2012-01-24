<?php

print '<table cellpadding="4" cellspacing="0" class="itemTable">';
/* @var $oDdo Ddo */
$oDdo = $d['o'];
foreach ($oDdo->fields as $f) {
  $el = $d[$f['name']]; // $el содержит текущее значение элемента записи
  if (!($t = $oDdo->el($el, $f['name'], $d['id']))) continue;
  print '<tr>';
  print '<td class="tit">'.$f['title'].':</td>';
  print '<td class="val">'.$t.'</td>';
  print '</tr>';
}
print '</table>'.
  '<p><a href="'.$d['pagePath'].'/'.$d['id'].'">Перейти к записи →</a></p><hr />';