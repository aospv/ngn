<? Tt::tpl('admin/modules/tags/header', $d) ?>

<form action="<?= Tt::getPath(3) ?>" method="post">
  <input type="hidden" name="action" value="create" />
  <p>Название:</p>
  <input type="text" name="title" class="fldLarge" />
  <p><input type="submit" value="Создать" /></p>
</form>