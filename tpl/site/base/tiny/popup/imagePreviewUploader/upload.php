<center>Фото загружено</center>

<script type="text/javascript">
ImageUploaderDialog.insertPreview(
  '<?= Misc::getFilePrefexedPath($d['url'], 'md_', 'jpg') ?>',
  '<?= Misc::getFilePrefexedPath($d['url'], 'sm_', 'jpg') ?>'
);
parent.document.currentDialog.close();
</script>
