<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title><?= SITE_TITLE . Config::getVar('separator') . get('title') ?></title>
  <meta http-equiv="Content-Type" content="text/html;charset=<?= CHARSET ?>">
  <link rel="stylesheet" type="text/css" href="./i/css/common.css">
  <link rel="stylesheet" type="text/css" href="./i/css/admin/main.css">
  <script type="text/javascript" src="./i/js/firebug.js"></script>
  <script type="text/javascript" src="./i/js/mootools/mootools-trunk-1544.js"></script>
</head>
<body>

<div class="admin">
  <div class="navTop">
    <a href="<?= Tt::getPath() ?>?logout=1" class="logout"><i></i>Выход</a>
    <div class="clear"><!-- --></div>
  </div>
  <h1><?= get('title') ?></h1>

