<? if ($d['page']) { ?>
<div class="gray"><? Tt::tpl('admin/modules/pages/header', $d) ?></div>
<? } ?>
<div class="navSub iconsSet">
  <a href="<?= Tt::getPath(2) ?>" class="list"><i></i>Список всех привилегий</a>
  <a href="<?= Tt::getPath(3) ?>/new" class="add"><i></i>Добавить привилегии</a>
  <a href="<?= params(2) ? Tt::getPath(3).'/pagePrivileges' : Tt::getPath(2) ?>?a=cleanup" class="cleanup"><i></i>Очистить пустые привилегии</a>
  <div class="clear"><!-- --></div>
</div>

