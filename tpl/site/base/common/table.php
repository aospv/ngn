<style>
.simpleTable td {
padding: 5px;
line-height: 1.3em;
}
</style>
<table class="simpleTable">
<? foreach ($d as $row) { ?>
<tr>
  <? foreach ($row as $v) { ?>
    <td><?= is_array($v) ? getPrr($v) : $v ?></td>
  <? } ?>
</tr>
<? } ?>
</table>