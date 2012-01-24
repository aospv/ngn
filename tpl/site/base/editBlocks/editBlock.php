<div class="editBlock smIcons bordered">
  <a href="<?= isset($d['path']) ? $d['path'] : Tt::getPath(1) ?>/<?= $d['id'] ?>?a=edit" class="sm-edit" title="Редактировать"><i></i></a>
  <a href="<?= Tt::getPath() ?>?a=<?= $d['active'] ? 'deactivate' : 'activate' ?>&itemId=<?= $d['id'] ?>" class="<?= $d['active'] ? 'sm-deactivate' : 'sm-activate' ?>" title="<?= $d['active'] ? 'Скрыть' : 'Отобразить' ?>"><i></i></a>
  <a href="<?= isset($d['path']) ? $d['path'] : Tt::getPath(1) ?>/<?= $d['id'] ?>?a=delete" class="sm-delete" title="Удалить"><i></i></a>
</div>
