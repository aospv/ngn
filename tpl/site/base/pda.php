<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="en"> 
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
  <title><?= $d['pageHeadTitle'] ?></title>
  <base href="<?= O::get('Req')->getAbsSiteBase() ?>/" />
  <?= SFLM::getCssTags('common') ?>
  <link rel="stylesheet" type="text/css" href="./i/css/common/screen.css" media="screen, projection" />
  <?= SFLM::getJsTags(SITE_SET) ?>
  <style>
  .pda .contents {
  padding-left: 7px;
  padding-right: 7px;
  }
  .hMenu {
  font-size: 14px;
  margin-bottom: 10px;
  }
  .hMenu li {
  margin-bottom: 0px;
  }
  .hMenu > ul {
  margin-left: 0px;
  }
  .hMenu > ul > li {
  float: left;
  border-left: 1px solid #CCCCCC;
  padding-left: 7px;
  padding-right: 7px;
  list-style: none;
  }
  .hMenu > ul > li > a {
  font-weight: bold;
  }
  </style>
</head>
<body>
<div class="pda">
  <div id="menu" class="hMenu">
    <?= Menu::ul('main', 1, '`<a href="`.$link.`"><span>`.$title.`</span></a><i></i><div class="clear"></div>`') ?>
    <div class="clear"></div>
  </div>
  <div class="contents">
    <? Tt::tpl('common/pathNav', $d) ?>
    <? Tt::tpl('common/pageTitle', $d) ?>
    <? Tt::tpl($d['tpl'], $d) ?>
  </div>
</div>
</body>
</html>
