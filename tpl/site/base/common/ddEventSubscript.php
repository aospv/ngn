<?
if ($d['subscriptType'] == 'none') return;
?>
<p>
  <? if ($d['subscriptType'] != 'editOnly') { ?>
  <a href="<?= $d['page']['path'].'/'.$d['itemId'] ?>">Посмотреть</a> или
  <? } ?>
  <a href="<?= $d['page']['path'].'/'.$d['itemId'].'?a=edit&itemId='.$d['itemId'] ?>">отредактировать</a> запись
</p>
