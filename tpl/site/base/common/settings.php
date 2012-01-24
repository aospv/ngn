<? if (empty($d['name'])) $d['name'] = 'settings' ?>
<? if ($d['structure']) { ?>
<table cellpadding="0" cellspacing="0" id="itemsTable">
<? foreach ($d['structure'] as $k => $title) { ?>
  <tr>
    <td><?= $title ?>:</td>
    <td>
      <input type="text" 
        name="<?= $d['name'] ?>[<?= $k ?>]" 
        value="<?= isset($d['values'][$k]) ? $d['values'][$k]  : '' ?>" />
    </td>
<? } ?>
</table>
<? } else { ?>
<p>Нет структуры настроек</p>
<? } ?>