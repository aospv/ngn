<? Tt::tpl('admin/modules/privileges/header', $d) ?>

<? if ($d['page']['isLock']) { ?>
  <div class="error" style="width:400px;">
    <div class="icon"><!-- --></div>
    <p><b>Доступ к данному разделу ограничен</b></p>
    <p>Вы можете <b><a class="confirm" href="<?= Tt::getPath() ?>?a=unlockPage">снять это ограничение</a></b> или 
    <a href="<?= Tt::getPath().'?a=new' ?>">добавить пользователей с привилегией <b>view</b></a> для возможности просмотра ими этого раздела.</p>
  </div>
<? } else { ?>
  <div class="info" style="width:400px;">
    <div class="icon"><!-- --></div>
    <p><b>Просмотр раздела открыт</b></p>
    <p>Вы можете <b><a class="confirm" href="<?= Tt::getPath() ?>?a=lockPage">ограничить доступ</a></b> к этому разделу. 
    После этого раздел будет доступен для просмотра только определенным вами пользователям.</p>
  </div>
<? } ?>

<? if ($d['privs']) { ?>

<h3>Общие привилегии</h3>

<form action="<?= Tt::getPath() ?>" method="POST">
<input type="hidden" name="action" value="updateByPage" />
<input type="hidden" name="pageId" value="<?= $d['page']['id'] ?>" />

<table cellpadding="0" cellspacing="0" id="itemsTable" class="valign">
<tr>
  <th>Пользователь</th>
  <th>Тип привилегии</th>
</tr>
<? foreach ($d['privs'] as $userId => $v) { ?>
<tr>
  <td><b><?= $v['login'] ?></b></td>
  <td>
    <? foreach ($d['types'] as $type => $title) { ?>
      <label for="type<?= $userId ?>_<?= $type ?>"><input type="checkbox"
       name="priv[<?= $userId ?>][<?= $type ?>]" id="type<?= $userId ?>_<?= $type ?>"
       <?= in_array($type, $v['types']) ? ' checked' : '' ?> value="1" />
      <?= $title ?></label><br />
    <? } ?>
  </td>
</tr>
<? } ?>
</table><br />
<input type="submit" value="Сохранить" style="width:200px; height: 30px;" />
</form>

<? } else { ?>
  <div class="iconsSet">
    <a href="<?= Tt::getPath(2).'/'.$d['page']['id'].'/new' ?>" class="add gray"><i></i>Добавить привилегии для раздела <b><?= $d['page']['title'] ?></b></a>
    <div class="clear"><!-- --></div>
  </div>
<? } ?>