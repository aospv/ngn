<form action="" method="POST" enctype="multipart/form-data" id="fileform">
  <input type="hidden" name="a" value="upload" />
  <p>
    Заголовок файла:<br />
    <input type="text" name="title" value="" id="fileTitle" />
  </p>
  <p>
    <input type="file" name="file" />
  </p>
</form>

<script type="text/javascript">
function okAction() {
  $('fileform').submit();
}	
</script>