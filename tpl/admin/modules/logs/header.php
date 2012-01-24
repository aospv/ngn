<div class="navSub iconsSet">
  <? foreach ($d['logs'] as $name) { ?>
    <a href="<?= Tt::getPath(2).'/'.$name ?>" class="list<?= $name == $d['logName'] ? ' sel' : '' ?>"><i></i><?= $name ?></a>
  <? } ?>
  <div class="clear"><!-- --></div>
</div>
<div class="navSub iconsSet">
  <a href="<?= Tt::getPath(2).'/'.$d['logName'].'?a=cleanup' ?>" class="delete confirm"><i></i>Очистить лог <b><?= $d['logName'] ?></b></a>
  <a href="<?= Tt::getPath(2).'/'.$d['logName'].'?a=delete' ?>" class="delete confirm"><i></i>Удалить лог <b><?= $d['logName'] ?></b></a>
  <div class="clear"><!-- --></div>
</div>
