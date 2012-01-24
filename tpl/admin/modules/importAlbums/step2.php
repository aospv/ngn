<h2>Шаг 2. Просмотр загруженных альбомов</h2>
<style>
#btnNext {
margin-left: 10px;
}
input[type=submit], input[type=button] {
padding: 0px 10px 0px 10px;
height: 30px;
}
</style>
<div class="items">
<? foreach ($d['dirs'] as $k => $v) { ?>
  <div class="item">
    <div class="smIcons bordered"><a href="<?= Tt::getPath(3) ?>?a=deleteFolder&n=<?= $k ?>" class=" sm-delete"><i></i></a></div>
    <?= $v['title'] ?>
    <i class="gray">— файлов в папке <?= $v['files'] ?> шт., размер папки: <?= File::format2($v['size']) ?></i>
  </div>
<? } ?>
</div>

<!-- 
<div class="iconsSet icon_info info"><i></i>
  Созданным альбомам будут даны автоматические названия.<br />
  Переименуйте их после импортирования.
</div>
 -->

<input type="submit" value="← Вернуться к шагу 1" onclick="window.location='<?= Tt::getPath(3) ?>'" />
<input type="submit" value="Удалить загруженные папки" id="btnDeleteAllFolders" />
<input type="submit" value="Импортировать альбомы (<?= count($d['dirs']) ?> шт.) →" id="btnImport" />

<script>
$('btnDeleteAllFolders').addEvent('click', function() {
  window.location = '<?= Tt::getPath() ?>?a=deleteAllFolders';
});
$('btnImport').addEvent('click', function() {
  new Ngn.Dialog.Loader.Simple({title: 'Идёт импортирование изображений. Подождите'});
  window.location = '<?= Tt::getPath() ?>?a=import';
});
</script>
