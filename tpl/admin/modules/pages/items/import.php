<h2>Импортирование записей для раздела «<?= $d['page']['title'] ?>»</h2>

<form action="<?= Tt::getPath() ?>?a=saveImportFile" method="post" enctype="multipart/form-data">
  <p>файл: <input type="file" name="file" /></p>
  <p><input type="submit" value="Импортировать" /></p>
</form>