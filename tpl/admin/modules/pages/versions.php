<? Tt::tpl('admin/modules/pages/header', $d) ?>
<table cellspacing="0" id="itemsTable" class="valign">
<? foreach ($d['items'] as $item) { ?>
<tr>
  <td>
    <a class="iconBtn versions" title="Откатить"
      href="<?= Tt::getPath(4) ?>?a=rollBack&itemId=<?= $item['id'] ?>&dateBackup=<?= $item['dateBackup'] ?>"><i></i></a>
  </td>
<? foreach ($item as $v) { ?>
  <td><?= Misc::cut($v, 300) ?>&nbsp;</td>
<? } ?>
</tr>
<? } ?>
</table>