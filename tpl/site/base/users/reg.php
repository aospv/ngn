<? if ($d['params'][2] == 'complete') { ?>
  <p>Регистрация прошла успешно</p>
<? } else { ?>
  <?= Slice::html('beforeLostPass', 'Перед формой регистраци') ?>
  <div class="apeform"><?= $d['form'] ?></div>
<? } ?>