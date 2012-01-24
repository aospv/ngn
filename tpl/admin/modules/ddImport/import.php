<? Tt::tpl('admin/modules/ddImport/header', $d) ?>
<form action="<?= Tt::getPath(3).'/makeImport' ?>" method="post">
  <p><input type="submit" value="Импортировать" style="width:150px;height:20px;"/></p>
</form>

<table cellpadding="0" cellspacing="0" id="itemsTable" class="valign">
<thead>
<tr>
<? foreach ($d['fields'] as $v) { ?>
  <td><b><?= $v['title'] ?></b></td>
<? } ?>
</tr>
</thead>
<tbody>
<? $n=0; foreach ($d['data'] as $row) { $n++; if ($n == 10) break; ?>
<tr>
<? foreach ($row as $v) { ?>
  <td><?= $v ?></td>
<? } ?>
</tr>
<? } ?>
</tbody>
</table>
<? if (count($d['data']) > 10) { ?>
<p class="info">Всего <b><?= count($d['data']) ?></b> строк для импорта</p>
<? } ?>
