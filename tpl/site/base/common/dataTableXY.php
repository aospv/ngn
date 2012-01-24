<table cellspacing="0" cellpadding="0">
<tr>
  <th><?= $d['xTitle'] ?></th>
<? foreach ($d['data'] as $k => $v) { ?>
  <td><?= $k ?></td>
<? } ?>
</tr>
<tr>
  <th><?= $d['yTitle'] ?></th>
<? foreach ($d['data'] as $k => $v) { ?>
  <td><?= $v ?></td>
<? } ?>
</tr>
</table>