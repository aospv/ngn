<div class="navSub iconsSet">
  <a href="<?= Tt::getPath() ?>" class="list"><i></i>Полея структуры «<b><?= $d['strData']['title'] ?></b>»</a>
  <a href="<?= Tt::getPath(1) ?>/ddStructure" class="list"><i></i>Структуры</a>
  <a href="<?= Tt::getPath(1) ?>/ddOutput/<?= $d['strData']['name'] ?>" class="list"><i></i>Управление выводом полей</a>
  <a href="<?= Tt::getPath() ?>?a=new" class="add"><i></i>Создать поле</a>
  <a href="<?= Tt::getPath(1).'/ddStructure?a=edit&id='.$d['strData']['id'] ?>" class="edit"><i></i>Редактировать структуру <b><?= $d['strData']['title']?></b></a>
  <a href="<?= Tt::getPath() ?>?a=import" class="import"><i></i>Импорт полей</a>
  <a href="<?= Tt::getPath() ?>?a=deleteAll" class="confirm delete"><i></i>Удалить все поля</a>
  <div class="clear"><!-- --></div>
</div>
