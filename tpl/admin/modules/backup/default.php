<p class="info">
  Максимальное кол-во бэкапов: <?= CurrentSiteBackup::$maxBackups ?>.
  Новый бэкап перезатрёт самый первый
</p>
<a href="<?= Tt::getPath(2).'?a=make' ?>" class="btn" id="btnMakeBackup"><span>Сделать резервную копию</span></a>

<? if ($d['items']) { ?>
<hr />
<h2>Существующие резервные копии:</h2>
<table id="itemsTable" class="backups" cellspacing="0">
<? $n=0; foreach ($d['items'] as $v) { $n++; ?>
<tr>
  <td><h2><?= $n ?>.</h2></td>
  <td>
    <a href="<?= Tt::getPath(2).'?a=restore&id='.$v['id'] ?>" class="btn" data-time="<?= datetimeStr($v['time']) ?>"><span>Восстановить из резервной копии от <?= datetimeStr($v['time']) ?></span></a>
  </td>
  <td>
    <div class="smIcons"><a href="<?= Tt::getPath(2).'?a=delete&id='.$v['id'] ?>" class="sm-delete gray confirm"><i></i>Удалить</a></div>
  </td>
</tr>
<? } ?>
</table>
<? } else { ?>
<p class="info">
  Не сделано ниодной резервной копии.
</p>
<? } ?>

<script>
$('btnMakeBackup').addEvent('click', function(e) {
  if (!confirm('Вы уверены, что хотите сделать резервную копию?')) return false;
  new Ngn.Dialog.Loader.Simple({title: 'Подождите, происходит создание резервной копии'});
});
var items = $('itemsTable');
if (items) {
  items.getElements('.btn').each(function(el) {
    el.addEvent('click', function() {
      if (!confirm('Вы уверены, что хотите заменить сайт резервной копией от ' + el.get('data-time'))) return false;
      new Ngn.Dialog.Loader.Simple({title: 'Подождите, происходит восстановление из резервной копии от ' + el.get('data-time')});
      Ngn.localStorage.clean();
    });
  });
}
</script>
