<p>(Максимальный размер файла: <b><?= ini_get('upload_max_filesize') ?></b>)</p>
<form action="" method="POST" enctype="multipart/form-data" id="imageform">
  <input type="hidden" name="a" value="upload" />
  <p><input type="file" name="image" /></p>
  <div id="thumbBlock">
    <p>
      Размеры превьюшки: <?= '<b>'.$d['dd']['smW'].'</b>x<b>'.$d['dd']['smH'].'</b>' ?>
    </p>
    <p>
      <label for="resample">
        <input type="radio" name="resizeType" value="resample" id="resample"<?= $d['dd']['resizeType'] == 'resample' ? ' checked' : '' ?> /> вписывать</label>
      &nbsp;
      <label for="resize">
        <input type="radio" name="resizeType" value="resize" id="resize"<?= $d['dd']['resizeType'] == 'resize' ? ' checked' : '' ?> /> обрезать</label>
    </p>
  </div>
</form>

<script type="text/javascript">
function changeIsThumb(flag) {
  document.getElementById('thumbBlock').style.display = (flag ? 'block' : 'none');
}
function okAction() {
  $('imageform').submit();
}
</script>