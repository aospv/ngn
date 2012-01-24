<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?= $d['pageTitle'] ?></title>
  <script type="text/javascript" src="<?= $d['base'] ?>i/js/tiny_mce/tiny_mce_popup.js"></script>
  <?= SFLM::getCssTags('admin') ?>
  <?= SFLM::getJsTags('admin') ?>
  <? if ($d['js']) { ?>
  <script type="text/javascript" src="<?= $d['js'] ?>"></script>
  <? } ?>
  <base href="<?= $d['base'] ?>" target="_self" />
  <link rel="stylesheet" type="text/css" href="/i/css/common/tinyDialog.css" media="screen, projection" />
</head>
<body>
  <? Tt::tpl($d['tpl'], $d) ?>
  <script type="text/javascript">
  tinyMCEPopup.restoreSelection();
  parent.document.currentDialog.setTitle('<?= $d['pageTitle']?>');
  </script>
</body>
</html>