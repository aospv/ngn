<script type="text/javascript">
window.addEvent('domready', function(){
  Ngn.initConfigManager();
});
</script>

<link rel="stylesheet" type="text/css" href="./i/css/admin/configManager.css" media="screen, projection" />

<?

$links = array();
if ($d['configType'] == 'vvv') {
  $links[] = array(
    'title' => 'Константы',
    'class' => 'config',
    'link' => Tt::getPath(2).'/constants',
  );
} else {
  $links[] = array(
    'title' => 'Переменные',
    'class' => 'config',
    'link' => Tt::getPath(2).'/vvv',
  );
}

foreach ($d['sections'] as $name => $title) {
  $links[] = array(
    'title' => $title,
    'class' =>
      (SiteConfig::hasSiteVar($name) ? 'siteConfig' : 'list').
      ($name == $d['configName'] ? ' sel' : ''),
    'link' => Tt::getPath(3).'/'.$name
  );
}

Tt::tpl('admin/common/module-header', array('links' => $links));

if ($d['canUpdate']) {
  Tt::tpl('admin/common/module-header', array('links' => array(
    array(
      'title' => 'Восстановить значения по-умолчанию для этой секции',
      'class' => 'refrash confirm',
      'link' => Tt::getPath(4).'?a=deleteSiteConfig'
    )
  )));
}

?>

<div class="mbody">
  <!--
  <div class="info">
    <div class="icon"></div>
    <? if ($d['saved']) { ?><b>Информация сохранена</b><? } ?>
    <?= LANG_AM_configManager_info ?>
  </div>
  <div class="info"><i></i><?= $d['isMaster'] ? 'MASTER' : 'SLAVE' ?>-конфиг</div>
  -->
  <? if (strstr($d['curName'], 'ips')) { ?>
    <div class="info"><i></i>Текущий IP: <?= $_SERVER['REMOTE_ADDR'] ?></div>
  <? } ?>
  <div id="vars" class="apeform">
    <?= $d['form'] ?> 
  </div>
</div>
