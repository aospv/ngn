<div class="editBlock smIcons bordered">
  <? if (!$d['active']) { ?>
    <a href="<?= Tt::getPath(1) ?>?a=publish&itemId=<?= $d['id'] ?>" class="sm-publish" title="Опубликовать" onclick="if (confirm('Уверены?')) window.location = this.href; return false;"><i></i></a>
  <? } ?>
  <a href="<?= Tt::getPath(1) ?>?a=edit&itemId=<?= $d['id'] ?>" class="sm-edit" title="Редактировать"><i></i></a>
  <a href="<?= Tt::getPath(1) ?>?a=delete&itemId=<?= $d['id'] ?>" class="sm-delete" title="Удалить" onclick="if (confirm('Уверены?')) window.location = this.href; return false;"><i></i></a>
</div>
