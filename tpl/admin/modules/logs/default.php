<? Tt::tpl('admin/modules/logs/header', $d) ?>
<table cellpadding="0" cellspacing="0" id="itemsTable" class="valign">
<thead>
  <tr>
  </tr>
</thead>
<tbody>
<? foreach ($d['items'] as $k => $v) { ?>
  <tr>
    <td nowrap><small><?= datetimeStr($v['time']) ?></small></td>
    <td width="30%"><?= $v['body'] ?></td>
    <td width="70%"><?= ol(explode('<br />', $v['trace'])) ?></td>
  </tr>
<? } ?>
</tbody>
</table>

