<? Tt::tpl('admin/modules/slices/header', $d) ?>
<div class="slice t_<?= $d['slice']['type'] ?>">
<form method="post" action="<?= Tt::getPath()?>?action=update">
  <textarea name="text" id="tinyEdit"><?= $d['slice']['text'] ?></textarea>
  <input type="submit" value="Сохранить" style="margin-top: 5px; width: 150px; height  : 30px;" />
</form>
</div>
<? if ($d['slice']['type'] == 'html') { ?>
<script type="text/javascript">
tinyMCE.init(new Ngn.TinySettings().getSettings({
  'elements': 'tinyEdit',
  'attachId': '<?= $d['attachId'] ?>'
}));          
</script>
<? } ?>