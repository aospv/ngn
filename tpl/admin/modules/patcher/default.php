<table cellpadding="0" cellspacing="0" id="itemsTable" class="valign">
<tr>
  <td>№ последнего примененного патча проекта <?= SITE_TITLE ?>:</td>
  <td><?= O::get('DbPatcher')->getSiteLastPatchN() ?></td>
</tr>
<tr>
  <td>№ последнего актуального патча:</td>
  <td><?= O::get('DbPatcher')->getNgnLastPatchN() ?></td>
</tr>
</table>
<br />
<table cellpadding="0" cellspacing="0" id="itemsTable" class="valign">
<tr>
  <th>Номер</th>
  <th>Название</th>
  <th>Описание</th>
  <th>&nbsp;</th>
</tr>
<? foreach($d['patches'] as $v) { ?>
  <tr>
    <td><?= $v['patchN'] ?></td>
    <td><b><?= $v['title'] ?>&nbsp;</b></td>
    <td><?= $v['descr'] ? $v['descr'] : '<i>Нет описания</i>' ?></td>
    <td>
      <? if ($v['status'] == 'complete') { ?>
        <a href="<?= Tt::getPath() ?>?a=cancel&patchN=<?= $v['patchN'] ?>">отменить</a>
      <? } else { ?>
        <a href="<?= Tt::getPath() ?>?a=make&patchN=<?= $v['patchN'] ?>">применить</a>
      <? } ?>
    </td>
  </tr>
<? } ?>
</table>
