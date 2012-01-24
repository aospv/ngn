<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title><?= $d['pageTitle'] ?></title>
  <meta http-equiv="Content-Type" content="text/html;charset=<?= CHARSET ?>">
  <base href="<?= $d['base'] ?>/" />
  <link rel="icon" href="./i/img/ngn/favicon.ico" type="image/x-icon" />
  <?= SFLM::getCssTags('cp') ?>
  <?= SFLM::getJsTags('cp') ?>
  <script type="text/javascript">
  window.addEvent('domready', function() {
    Ngn.cp.init();
  });
  </script>
</head>
