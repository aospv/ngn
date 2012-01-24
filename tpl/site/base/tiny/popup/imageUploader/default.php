<p>(Максимальный размер файла: <b><?= ini_get('upload_max_filesize') ?></b>)</p>
<p>
<form action="" method="POST" enctype="multipart/form-data" id="imageform">
  <input type="hidden" name="a" value="upload" />
  <input type="file" name="image" />
</form>
</p>

<script type="text/javascript">
function okAction() {
  $('imageform').submit();
}
</script>