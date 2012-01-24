<? if ($d['items']) { ?>
  <?= Html::select($d['name'], $d['items'], null, array('tagId', $d['name'])); ?>
<? } else { ?>
  Ничего не найдено
<? } ?>