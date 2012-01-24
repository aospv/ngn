<? if (isset($d['saved'])) { ?>
  <div class="info">Изменения внесены успешно</div>
<? } else { ?>
<div class="apeform">
  <?= $d['form'] ?>
</div>
<? } ?>