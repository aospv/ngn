<table>
<? foreach ($d as $row) { ?>
<tr>
  <? $n=0; foreach ($row as $v) { ?>
    <td><?= $v.(!$n ? ':' : '') ?></td>
  <? $n++; } ?>
</tr>
<? } ?>
</table>