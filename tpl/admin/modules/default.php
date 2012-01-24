<? if ($d['action'] == 'cleanup') { ?>
<script type="text/javascript">
Ngn.localStorage.clean();
//window.location = Ngn.getPath(1);
</script>
<? die(); } ?>

Вы находитесь в панеле управления сайтом <b><?= SITE_TITLE ?></b>

<img src="http://asite.ru/ngn-admin-ping/index.php?site=<?= SITE_DOMAIN ?>" width="1" height="1" />

<?= Config::getVarVar('adminExtras', 'homeHtml', true) ?>

<hr />

<div class="col">
<? if (Misc::isGod()) { ?>
  <h2>Техническая информация</h2>
  <h3>Сборка</h3>
  <ul>
    <li><b>Номер текущей сборки:</b><br /><?= BUILD ?></li>
    <li><b>Дата и время создания сборки:</b><br /><?= datetimeStr(BUILD_TIME) ?></li>
  </ul>
  <h3>Размер</h3>
  <?
  /*
  $s1 = Dir::getSize(WEBROOT_PATH);
  $s2 = Db::getSize(DB_NAME, db());
  ?>
  <ul>
    <li><b>Файлы:</b> <?= File::format($s1) ?></li>
    <li><b>БД:</b> <?= File::format($s2) ?></li>
    <li><b>Вместе:</b> <?= File::format($s1+$s2) ?></li>
  </ul>
  <?php *//*
  <h3>Патчи базы данных</h3>
  <ul>
    <li><b>Номер последнего применённого БД-патча:</b><br /><?= O::get('DbPatcher')->getSiteLastPatchN() ?></li>
    <li><b>Номер последнего доступного БД-патча:</b><br /><?= O::get('DbPatcher')->getNgnLastPatchN() ?></li>
  </ul>
  <? if (O::get('DbPatcher')->getActualPatches()) { ?>
    <input type="button" value="Применить актуальные патчи" style="width:200px; height:30px;" 
      onclick="window.location='<?= Tt::getPath(1).'/patcher/patch' ?>'" />
  <? } ?>
  
  <input type="button" value="Проверить наличие новой сборки" id="btnCheckNewBuild"
    style="width:300px;height:20px;margin-top:10px;" />
  */?>

  <script type="" src="./i/js/ngn/Ngn.cp.Updater.js"></script>  
  <script type="text/javascript">
  var btnCheckNewBuild = $('btnCheckNewBuild');
  if (btnCheckNewBuild) {
    btnCheckNewBuild.addEvent('click', function(e){
      new Ngn.Updater('<?= Tt::getPath(0) ?>', <?= BUILD ?>).check();
    });
  }
  </script>
<? } ?>
</div>
<?php /*
<div class="col">
  <h2>Новости NGN</h2>
  <iframe src="http://<?= UPDATER_URL ?>/c/panel/ngnNews" style="width:100%; height:200px; border: 0px;"></iframe>
</div>
<div class="clear"><!-- --></div>
*/?>