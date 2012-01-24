<form method="post" action="<?= Tt::getPath()?>?action=update">
  <textarea name="text" id="tinyEdit" style="width:80%; height:300px;"><?= $d['text'] ?></textarea>
  <input type="submit" value="Сохранить" style="margin-top: 5px; width: 150px; height  : 30px;" />
</form>
<script type="text/javascript">
tinyMCE.init(new Ngn.TinySettings().getSettings({
  'elements': 'tinyEdit',
  'attachId': '<?= $d['attachId'] ?>'
}));          
</script>