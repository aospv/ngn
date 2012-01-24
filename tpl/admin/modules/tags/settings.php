<? Tt::tpl('admin/modules/tags/header', $d) ?>

<h2>Настройки</h2>

<form action="<?= Tt::getPath() ?>" method="post">
  <input type="hidden" name="action" value="updateSettings" />
  
  <h3>Метод отображения</h3>
  <label for="flat"><input type="radio" name="method" value="flat" id="flat" <?= $d['method'] == 'flat' ? 'checked' : '' ?>/> линейные тэги</label>
  <label for="tree"><input type="radio" name="method" value="tree" id="tree"  <?= $d['method'] == 'tree' ? 'checked' : '' ?>/> древовидные тэги</label>
  <br /><br />
  
  <h3>Уникальность имён тэгов</h3>
  <label for="unical1"><input type="radio" name="unical" value="1" id="unical1" <?= $d['unical'] ? 'checked' : '' ?>/> имента тэгов уникальны</label>
  <label for="unical0"><input type="radio" name="unical" value="0" id="unical0"  <?= !$d['unical'] ? 'checked' : '' ?>/> не уникальны</label>

  <p style="margin-top:15px;"><input type="submit" value="Сохранить" style="width:200px;height:30px;" /></p>
</form>
