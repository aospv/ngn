<? Tt::tpl('admin/modules/privileges/header') ?>

<div class="cols">
<div class="col" style="width:400px;">
<h3>Общие привилегии</h3>
<form action="<?= Tt::getPath() ?>" method="POST">
<input type="hidden" name="action" value="updateByUser" />
<input type="hidden" name="userId" value="<?= $d['user']['id'] ?>" />

<table cellpadding="0" cellspacing="0" id="itemsTable" class="valign">
<tr>
  <th>Страница</th>
  <th>Тип привилегии</th>
</tr>
<? foreach ($d['privs'] as $pageId => $v) { ?>
<tr>
  <td><b><?= $v['title'] ?></b></td>
  <td>
    <? foreach ($d['types'] as $type => $title) { ?>
      <label for="type<?= $pageId ?>_<?= $type ?>"><input type="checkbox"
       name="priv[<?= $pageId ?>][<?= $type ?>]" id="type<?= $pageId ?>_<?= $type ?>"
       <?= in_array($type, $v['types']) ? ' checked' : '' ?> value="1" />
      <?= $title ?></label><br />
    <? } ?>
  </td>
</tr>
<? } ?>
</table><br />
<input type="submit" value="Сохранить" style="width:200px; height: 30px;" />
</form>
</div>

<?php /*
<div class="col" style="width:400px;">
<h3>Привилегии для полей</h3>
<form action="<?= Tt::getPath() ?>" method="POST">
<input type="hidden" name="action" value="updateDD" />
<input type="hidden" name="userId" value="<?= $d['user']['id'] ?>" />

<? if ($d['ddFields']) { ?>
<table cellpadding="0" cellspacing="0" id="itemsTable" class="valign">
<tr>
  <th>Структура</th>
  <th>Имя поля</th>
</tr>
<? foreach ($d['ddFields'] as $v) { ?>
<tr>
  <td><b><?= $v['strName'] ?></b></td>
  <td>
    <? foreach ($v['fields'] as $field) { ?>
      <label for="privs<?= $v['strName'] ?>_<?= $field ?>">
      <input type="checkbox" name="privs[<?= $v['strName'] ?>][<?= $field ?>]" value="1" 
      <?= ($d['ddPrivs'][$v['strName']] and in_array($field, $d['ddPrivs'][$v['strName']])) ? ' checked' : '' ?>
      id="privs<?= $v['strName'] ?>_<?= $field ?>" /> <?= $field ?>
      </label><br />
    <? } ?>
  </td>
</tr>
<? } ?>
</table><br />
<? } else { ?>
<p>Не существует ниодного привилегированого поля</p>
<? } ?>
<input type="submit" value="Сохранить" style="width:200px; height: 30px;" />
</form>
</div>
*/?>
</div>