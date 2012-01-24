<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html lang="en"> 
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
  <meta content="NGN" name="generator"/>
  <title><?= $d['pageHeadTitle'] ?></title>
  <script type="text/javascript" src="/i/js/tiny_mce/tiny_mce.js"></script>
  <?= SFLM::getJsTags('site') ?>
  <?= SFLM::getCssTags('site') ?>
  
<script src="http://userapi.com/js/api/openapi.js" type="text/javascript" charset="windows-1251"></script>
  
</head>
<body>
<div id="layout" class="pageName_<?= Misc::name2id($d['page']['name'])?><?= $d['settings']['defaultAction'] == 'blocks' ? ' blocksLayout' : '' ?>">
  <div class="container showgrid">
    <? Tt::tpl($d['tpl'], $d) ?>
  </div>
</div>
</body> 
</html>
