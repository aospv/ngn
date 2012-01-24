<? //pr($d); ?>
<form action="<?= Tt::getPath()?>" method="POST">
  <input type="hidden" name="action" value="<?= $d['postAction'] ?>" />
  <input type="text" name="title" value="<?= $d['title'] ?>" /><br />
  <textarea name="descript"><?= $d['descript'] ?></textarea><br />
  <input type="submit" value="Сохранить" />
</form>