<form action="<?= Tt::getPath() ?>" method="POST">
  <input type="hidden" name="id" value="<?= $d['id']?>" />
  <input type="hidden" name="action" value="updatePage" />
  <input type="text" name="title" value="<?= $d['title']?>" />
  <input type="submit" value="Сохранить" />
</form>