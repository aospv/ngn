<div class="editBlock">
  <a href="<?= Tt::getPath(2) ?>?a=edit&id=<?= $d['id'] ?>" class="edit" id="btnEdit" title="Редактировать"><i></i></a>
  <a href="<?= Tt::getPath(2) ?>?a=<?= $d['active'] ? 'deactivate' : 'activate' ?>&id=<?= $d['id'] ?>" class="<?= $d['active'] ? 'deactivate' : 'activate' ?>" id="btnActive" title="<?= $d['active'] ? 'Скрыть' : 'Отобразить' ?>"><i></i></a>
  <a href="<?= Tt::getPath(2) ?>?a=delete&id=<?= $d['id'] ?>" class="delete" id="btnDelete" title="Удалить" onclick="if (confirm('Уверены?')) window.location = this.href; return false;"><i></i></a>
</div>
