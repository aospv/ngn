<div class="iconsSet">
  <a href="<?= Tt::getPath() ?>?a=edit" class="edit"><i></i><?= LANG_EDIT ?></a>
  <div class="clear"><!-- --></div>
</div>

<?

$v =& $d['data'];
foreach ($d['fields'] as $f) {
  if ($f['defaultDisallow']) continue;
  $a = $v[$f['name']];
  
?>

<div style="margin-top:10px;">
      <? if (is_array($a)) { ?>
        <? if ($a[0]['title']) { // Вывод списка для тэгов ?>
          <ul>
          <? foreach ($a as $t) { ?>
            <li><?= $t['title'] ?></li>
          <? } ?>
          </ul>
        <? } elseif ($a['title']) { ?>
          <?= $a['title'] ?>
        <? } ?>
      <? } else { ?>
        <? if ($f['type'] == 'textarea') { ?>
          <?= nl2br(Misc::cut($a, 300)) ?>
        <? } elseif ($f['type'] == 'file') { ?>
          <? if ($a) { ?>
            <a href="<?= $a ?>" target="_blank"><?= $a ?></a>
          <? } ?>
        <? } elseif ($f['type'] == 'image') { ?>
          <? if ($a) { ?>
            <a href="<?= $a ?>" target="_blank" class="thmb">
              <img src="<?= $v['sm_'.$f['name']] ?>" /></a>
          <? } else { ?>
           &nbsp;
          <? } ?>
        <? } else { ?>
          <?= Misc::cut($a, 500) ?>
        <? } ?>
      <? } ?>
</div>

<? } ?>