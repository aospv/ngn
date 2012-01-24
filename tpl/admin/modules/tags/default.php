<script type="text/javascript">
window.addEvent('domready', function(){
  new Ngn.ItemsTable({ 
    isSorting: false,
    deleteAction: 'deleteGroup'
  });
});
</script>

<style>
.tools .clear {
width: 55px;
}
.loader {
background-position: 48px 7px;
background-image: none;
}
.loading .loader {
background-image: url(./i/img/black/loader.gif);
}

</style>

<? if ($d['items']) { ?>
<table cellpadding="0" cellspacing="0" id="itemsTable">
<? foreach ($d['items'] as $str) { ?>
  <thead>
  <tr>
    <th colspan="2"><p style="margin-top: 7px; margin-bottom: 5px"><small class="gray"><b>Структура:</b></small> <?= $str['title'] ?></p></th>
  </tr>
  </thead>
  <tbody>
  <? foreach ($str['items'] as $v) { ?>
  <tr id="<?= 'item_'.$v['id'].'_'.$v['oid'] ?>">
    <td class="tools loader">
      <a href="<?= Tt::getPath(2).'/'.$v['id'].'/list' ?>" class="iconBtn edit" title="<?= LANG_EDIT ?>"><i></i></a>
      <a href="<?= Tt::getPath(2).'/'.$v['id'].'/list' ?>" class="iconBtn delete" title="<?= LANG_DELETE ?>"><i></i></a>
      <div class="clear"><!-- --></div>
    </td>
    <td><small class="gray">Поле: </small><?= $v['title'] ? $v['title'] : '<i class="gray">{поле удалено}</i>' ?></td>
  </tr>
  <? } ?>
  </tbody>
<? } ?>
</table>
<? } else { ?>
  Тэг групп не существут
<? } ?>