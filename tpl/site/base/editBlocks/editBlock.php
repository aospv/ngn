<? $path = isset($d['path']) ? $d['path'] : Tt::getPath(1); ?>
<div class="editBlock smIcons bordered">
  <a href="<?= $path ?>/<?= $d['id'] ?>?a=edit" class="sm-edit" title="Редактировать"><i></i></a>
  <a href="<?= $path ?>?a=<?= $d['active'] ? 'deactivate' : 'activate' ?>&itemId=<?= $d['id'] ?>" class="actv <?= $d['active'] ? 'sm-deactivate' : 'sm-activate' ?>" title="<?= $d['active'] ? 'Скрыть' : 'Отобразить' ?>"><i></i></a>
  <a href="<?= $path ?>/<?= $d['id'] ?>?a=delete" class="sm-delete" title="Удалить"><i></i></a>
</div>
