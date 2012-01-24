<? if (Auth::get('id') == $d['userId']) { ?>
<div class="smIcons bordered" style="float: right; ">
  <a href="#" class="sm-edit" title="Редактировать" id="editUserGruop"><i></i></a>
  <script>
  $('editUserGruop').addEvent('click', function(e) {
    e.preventDefault();
    new Ngn.site.userGroup.InfoDialog({
      url: '/userGroup?a=json_edit&id=<?= $d['id'] ?>',
    });
  });
  </script>
</div>
<? } ?>
<h2><?= $d['title'] ?></h2>
<p><img src="<?= '/'.UPLOAD_DIR.'/'.Misc::getFilePrefexedPath($d['image'], 'md_', 'jpg') ?>" /></p>
<?= $d['text'] ?>

