<div class="editBlock">
  <a href="<?= Tt::getPath(1) ?>?a=sub_edit&id=<?= $d['id'] ?>" class="edit" title="Редактировать"><i></i></a>
  <a href="<?= Tt::getPath(1) ?>?a=<?= $d['active'] ? 'sub_deactivate' : 'sub_activate' ?>&id=<?= $d['id'] ?>" class="<?= $d['active'] ? 'deactivate' : 'activate' ?>" title="<?= $d['active'] ? 'Скрыть' : 'Отобразить' ?>"><i></i></a>
  <a href="<?= Tt::getPath(1) ?>?a=sub_delete&id=<?= $d['id'] ?>" class="delete" title="Удалить" onclick="if (confirm('Уверены?')) window.location = this.href; return false;"><i></i></a>
</div>
