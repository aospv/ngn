<h2>Шаг 1. Загрузка архива с альбомами</h2>
<style>
#btnNext {
margin-left: 10px;
}
input[type=submit], input[type=button] {
padding: 0px 10px 0px 10px;
height: 30px;
}
</style>

<?php
if ($d['dirs']) $d['form'] = Html::inputAppend($d['form'], 'ok',
  '<input type="button" value="Перейти к шагу 2 →" id="btnNext" />');
?>

<div class="info"><i></i>
Загружаеммый вами архив должен содержать папки с изображениями.<br />
Каждая из этих папок будет импортирована, как отдельный альбом.<br />
<a href="./i/help/importAlbums.html" class="help">Пример структуры папок</a>
</div>

<div class="apeform"><?= $d['form'] ?></div>

<script>
var eBtnNext = $('btnNext');
if (eBtnNext) $('btnNext').addEvent('click', function() {
  window.location = Tt::getPath(3) + '/step2';
});
$('oki').addEvent('click', function() {
  new Ngn.Dialog.Loader.Simple({title: 'Идёт загрузка файла. Подождите'});
});
</script>
