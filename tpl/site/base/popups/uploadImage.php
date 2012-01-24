<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title><?= $d['pageTitle'] ?> : <?= SITE_TITLE ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=<?= CHARSET ?>" />
  <link rel="stylesheet" href="/m/css/main.css" type="text/css" />
</head>
<body>
  <div class="popup">
  <? if (!$d['file']) { ?>
    <form action="" method="POST" enctype="multipart/form-data">
      <center>
        <p><input type="file" name="image" /></p>
        <p><input type="submit" value="Вставить" style="width:250px;height:50px;" /></p>
      </center>
    </form>
  <? } else { ?>
    <p>Файл загружен: <b><?= $d['file'] ?></b></p>
    <script>
    window.opener.TSel.insPicCore('<?= $d['file'] ?>');
    window.close();
    </script>
  <? } ?>
  </div>
</body>
</html>