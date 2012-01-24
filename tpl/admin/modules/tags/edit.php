<? Tt::tpl('admin/modules/tags/header', $d) ?>

<? if (!$d['parent']) { ?>
  <h2>Изменение тэга <b><?= $d['tag']['title'] ?></b></h2>
<? } else { ?>
  <h2>Изменение подтэга <b><?= $d['tag']['title'] ?></b> для тэга <b><?= $d['parent']['title'] ?></b></h2>
<? } ?>
<form action="<?= Tt::getPath() ?>" method="post" style="width:500px;">
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="tagId" value="<?= $d['tag']['id'] ?>" />
  <p>Название:</p>
  <input type="text" name="title" class="fldLarge" value="<?= $d['tag']['title'] ?>" />
  <p><input type="submit" value="Сохранить" style="width:150px;height:30px;" /></p>
</form>