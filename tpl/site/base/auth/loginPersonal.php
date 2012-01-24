<?php 

if (($pageIds = Config::getVarVar('userReg', 'pageIds', true))) {
  $pages = db()->query('SELECT id, title, path FROM pages WHERE id IN (?a)',
    Arr::explodeCommas($pageIds));
} 

?>

<ul>
  <li>
    <? if (($path = Tt::getUserPath(Auth::get('id'), true))) { ?>
      <a href="<?= $path ?>"><b><?= Auth::get('login') ?></b></a><br />
    <? } else { ?>
      <b><?= Auth::get('login') ?></b><br />
    <? } ?>
  </li>
  <? if (!empty($pages)) foreach ($pages as $v) { ?>
    <li><a href="<?= $v['path'] ?>"><?= $v['title'] ?></a></li>
  <? } ?>
  <? if (($path = Tt::getControllerPath('userReg', true))) { ?><li><a href="<?= $path.'/editPass' ?>">Рег.данные</a></li><? } ?>
  <!--  
  <? if (($path = Tt::getControllerPath('notify', true))) { ?><li><a href="<?= $path ?>">Уведомления</a></li><? } ?>
  -->
    <? /**
        * @todo Сделать ссылку на админку
        */ ?>
  <? if (Misc::isAdmin()) { ?>
    <li><a href="/admin">Панель управления</a></li>
  <? } ?>
  <li><a href="<?= Tt::getPath() ?>?logout=1">Выйти</a></li>
</ul>
