<center>Файл <b><?= $d['url'] ?></b> загружен</center>

<script type="text/javascript">
FileUploaderDialog.insert(
  '<?= $d['url'] ?>',
  '<?= $d['title'] ?>',
  '<?= File::format2($d['filesize']) ?>'
);
parent.document.currentDialog.close();
</script>