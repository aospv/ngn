<? $d['pcd']['pageAdminSettings'] = $d['pageAdminSettings'] ?>
<? $d['pcd']['adminPageControllerSettings'] = $d['settings'] ?>
<div id="pageControllerSettings" style="display:none"><?= json_encode($d['pcd']['page']['settings']) ?></div>
<?
Tt::tpl('admin/modules/pages/twoPanels',
  array('rightPanelTpl' => $d['pcd']['tpl']) + $d['pcd']
);
?>
