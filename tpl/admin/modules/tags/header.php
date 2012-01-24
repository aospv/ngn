<div class="navSub iconsSet">
  <a href="<?= Tt::getPath(2) ?>" class="list"><i></i>Теговые поля</a>
  <a href="<?= Tt::getPath(3) ?>/list" class="list"><i></i>Теги поля <b>«<?= $d['field']['title'] ?>»</b></a>
  <a href="<?= Tt::getPath(1) ?>/ddField/<?= $d['field']['strName'].'?a=edit&id='.$d['field']['id'] ?>" class="edit"><i></i>Редактировать поле <b>«<?= $d['field']['title'] ?>»</b></a>
  <? if (!$d['itemsDirected']) { ?>
    <a href="<?= Tt::getPath(3) ?>/new" class="add"><i></i>Создать тэг</a>
    <a href="<?= Tt::getPath(3) ?>/import" class="import"><i></i>Импорт тэгов</b></a>
    <a href="<?= Tt::getPath() ?>?a=updateCounts" class="cleanup"><i></i>Пересчитать</b></a>
  <? } ?>
  <? if ($d['page']) { ?>
    <a href="<?= Tt::getPath(1).'/pages/'.$d['page']['pid'].'/'.$d['page']['id'].'/editContent' ?>" class="edit"><i></i>Редактировать <b>«<?= $d['page']['title'] ?>»</b></a>
  <? } ?>
  <div class="clear"><!-- --></div>
</div>

