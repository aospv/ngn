<?= SFLM::getJsTags('admin') ?>
<?= SFLM::getJsTags('upload') ?>

<link rel="stylesheet" type="text/css" href="./i/css/common/fancy.css" media="screen, projection" />

<ul id="fu-list" class="fu-list"></ul>
<a href="#" id="demo-attach-2" style="display: none;">Добавить ещё изображения</a>

<!-- 
    <div class="fu-item">
      <ul class="fu-list">
        <li id="file-1" class="file">
          <span class="file-title">LARGE.jpg</span>
          <div class="file-progress"
          style="background-position: 95% 0px" title="75%">
            <div class="file-progress-inner">
            </div>
          </div>
          <a class="file-cancel" href="#">Cancel</a>
        </li>
      </ul>
    </div>
 -->
 
<script type="text/javascript">
Ngn.uploadAttache('fu-list', '#demo-attach, #demo-attach-2', {
  url: window.location.pathname + '?a=json_upload&attachId=<?= $d['attachId'] ?>',
  fileSizeMax: <?= Misc::phpIniFileSizeToBytes(ini_get('upload_max_filesize')) ?>,
  multiple: true,
  onFileComplete: function(o) {
    ImagesUploaderDialog.insertImage('./'+JSON.decode(o.response.text).imagePath);
  },
  fileType: 'image'
});
</script>
