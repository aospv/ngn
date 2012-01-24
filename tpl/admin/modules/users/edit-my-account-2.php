<? Tt::tpl('admin/modules/users/profileHeader') ?>
<? if ($d['saved']) { ?>
  <p>Информация была сохранена</p>
  <p><a href="<?= Tt::getPath() ?>">← вернуться</a></p>
<? } else { ?>
  <div class="apeform"><?= $d['form'] ?></div>
<? } ?>