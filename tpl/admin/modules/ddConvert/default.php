<h2>Перенос структуры раздела <b><?= $d['pageData']['title'] ?></b></h2>

<p>Перед вами находятся соответствия, по которым будет осуществлён перенос<br /> 
данный из старой структуры <b><?= $d['curStrName'] ?></b> в
новую <b><?= $d['newStrName'] ?></b> для раздела
<b><a href="/<?= $d['pageData']['path'] ?>" target="_blank"><?= $d['pageData']['title'] ?></a></b></p><br/>

<form action="<?= Tt::getPath() ?>" method="post">
<input type="hidden" name="action" value="convert" />
<input type="hidden" name="referer" value="<?= $d['referer'] ?>" />
<table cellpadding="0" cellspacing="0" id="itemsTable">
<tr>
  <th>Поле старой структуры "<?= $d['curStrName'] ?>"</th>
  <th></th>
  <th>Поле новой структуры "<?= $d['newStrName'] ?>"</th>
</tr>
<? foreach ($d['curFields'] as $v) { ?>
<tr>
  <td><i><?= $v['title'] ?></i></td>
  <td>→</td>
  <td><?= Html::select('new_'.$v['name'], $d['newFields']['options'],
!empty($d['newFields']['options'][$v['name']]) ? $v['name'] : null) ?></td>
</tr>
<? } ?>
</table>
<input type="button" value="← Игнорировать"
 style="margin-top:15px;width:120px;height:30px;"
 onclick="window.location='<?= $d['referer'] ?>'" />
<input type="submit" value="Осуществить перенос →" style="margin-top:15px;width:180px;height:30px;" />
<input type="button" value="Изменить структуру без переноса"
 style="margin-top:15px;width:200px;height:30px;"
 onclick="$('updateStructureForm').submit()" />
</form>

<form action="<?= Tt::getPath() ?>" method="post" id="updateStructureForm">
<input type="hidden" name="action" value="updateStructure" />
<input type="hidden" name="newStrName" value="<?= $d['newStrName'] ?>" />
<input type="hidden" name="referer" value="<?= $d['referer'] ?>" />
</form>
