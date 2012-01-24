<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title><?=
    ($d['adminModuleTitle'] ? $d['adminModuleTitle'] : ''). 
    ($d['pageTitle'] ? ' / '.strip_tags($d['pageTitle']) : '').' — '.SITE_TITLE ?></title>
  <? Tt::tpl('admin/headers', $d) ?>
  <?= PageModuleCore::sf('admin', $d['page']['module']) ?>
  <script type="text/javascript">
  window.addEvent('domready', function() {
    Ngn.cp.init();
  });
  </script>
</head>
