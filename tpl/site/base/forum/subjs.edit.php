<form action="<?= Tt::getPath()?>" method="POST">
  <? if ($d['postAction'] == 'update') { ?>
    <input type="hidden" name="id" value="<?= $d['id'] ?>" />
  <? } ?>
  <input type="hidden" name="action" value="<?= $d['postAction'] ?>" />
  
  <p><b>Заголовок:</b></p>
  <p style="margin-bottom:10px;"><input type="text" name="title" value="<?= $d['title'] ?>" class="fldLarge" /></p>
  <p><b>Текст:</b></p>
  <p><textarea name="text" style="width:100%;height:100px;"><?= $d['text'] ?></textarea></p>
  <p style="margin-top:10px;"><input type="submit" value="Сохранить" style="width:150px;height:30px;" /></p>
</form>
