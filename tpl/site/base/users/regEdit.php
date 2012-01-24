<? if (isset($d['saved']) or $d['params'][2] == 'complete') { ?>
  <div class="info">Изменения внесены успешно</div>
<? } else { ?>
<div class="apeform">
  <?= $d['form'] ?>
</div>
<? } ?>