<? pr($d); ?>
<form action="<?= Tt::getPath()?>" method="POST">
  <input type="hidden" name="action" value="<?= $d['postAction'] ?>" />
  <textarea name="text"><?= $d['descript'] ?></textarea><br />
  <input type="submit" value="Сохранить" />
</form>