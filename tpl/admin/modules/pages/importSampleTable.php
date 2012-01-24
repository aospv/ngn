<table cellpadding="0" cellspacing="0" id="itemsTable">
<thead>
  <tr>
    <? foreach ($d[1] as $v) { ?>
      <th><?= $v ?></th>
    <? } ?>
  </tr>
</thead>
<tbody>
  <? for ($i=2; $i<count($d); $i++) { $l = $d[$i]; ?>
  <tr>
    <? foreach ($l as $v) { ?>
      <td><?= $v ?></td>
    <? } ?>
  </tr>
  <? } ?>
</tbody>
</table>
