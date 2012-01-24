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
