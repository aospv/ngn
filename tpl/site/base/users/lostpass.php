<? if ($d['params'][2] == 'complete') { ?>
  <p>Операция прошла успешно</p>
<? } elseif ($d['params'][2] == 'failed') { ?>
  <p>Ошибка отправки</p>
<? } else { ?>
  <?= Slice::html('beforeLostPass', 'Перед формой забытого пароля') ?>
  <div class="apeform"><?= $d['form'] ?></div>
<? } ?>