<script type="text/javascript">
window.addEvent('domready', function(){
  new Ngn.ItemsTable();
});
</script>

<? Tt::tpl('admin/modules/ddField/header', $d) ?>

<style>
#itemsTable .tools .clear { width: 90px }

.loader {
background-position: 80px 7px;
background-image: none;
}
.loading .loader {
background-image: url(./i/img/black/loader.gif) !important;
}

.t_header { background: #F1F0DF; }
</style>

<?
$items = array();
$items2 = array();
foreach ($d['items'] as $k => $v) if ($v['editable']) $items[$k] = $v;
if ($d['params'][0] != 'god') {
  foreach ($items as $k => $v) if (!$v['system']) $items2[$k] = $v;
  $d['items'] = $items2;
} else {
  $d['items'] = $items;
}
?>

<? if ($d['items']) { ?>
<table cellpadding="0" cellspacing="0" id="itemsTable">
<thead>
<tr>
  <th>&nbsp;</th>
  <th>Название</th>
  <th>Имя</th>
  <th>Тип</th>
  <th>&nbsp;</th>
  <th>Описание</th>
</tr>
</thead>
<tbody>
<? foreach ($d['items'] as $k => $v) { ?>
  <tr class="t_<?= $v['type'] ?><?= $v['defaultDisallow'] ? ' disallow' : '' ?>" id="<?= 'item_'.$v['id'] ?>">
    <td class="tools loader">
      <div class="dragBox"></div>
      <? if ($v['editable']) { ?>
      <a class="iconBtn delete" title="<?= LANG_DELETE ?>"
        href="<?= Tt::getPath() ?>?a=delete&id=<?= $v['id'] ?>"
        ><i></i></a>
      <a class="iconBtn edit" title="<?= LANG_EDIT ?>"
        href="<?= Tt::getPath() ?>?a=edit&id=<?= $v['id'] ?>"><i></i></a>
      <? } else { ?>
      &nbsp;
      <? } ?>
      <? if ($v['isTagType']) { ?>
        <a class="iconBtn tags" title="Редактировать теги"
          href="<?= Tt::getPath(1) ?>/tags/<?= $v['tagsGroupId'] ?>/list"><i></i></a>
      <? } ?>
      <div class="clear"><!-- --></div>
    </td>
    <td><?= $v['title'] ?><?= $v['required'] ? '<span style="color:#FF0000">*</span>' : '' ?></td>
    <td><i><?= $v['name'] ?></i></td>
    <td><i><img src="<?= DdFieldCore::getIconPath($v['type']) ?>" title="<?= $v['type'] ?>"></i></td>
    <td><small>
      <?= $v['notList'] ? '<nobr>{не выводится}</nobr>' : '' ?>
      <?= $v['system'] ? '<nobr>{системное}</nobr>' : '' ?>
      <?= $v['defaultDisallow'] ? '<nobr>{не доступно}</nobr>' : '' ?>
      <?= $v['editable'] ? '' : '<nobr>{не редактируется}</nobr>' ?>
    &nbsp;</small></td>
    <td><small><?= $v["descr"] ?>&nbsp;</small></td>
  </tr>
<? } ?>
</tbody>
</table>
<? } else { ?>
<p><?= LANG_NO_FIELDS ?>. <a href="<?= Tt::getPath() ?>?a=new"><?= LANG_CREATE ?>?</a></p>
<? } ?>