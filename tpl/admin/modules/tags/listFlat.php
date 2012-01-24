<script type="text/javascript">
window.addEvent('domready', function(){
  new Ngn.ItemsTable();
});
</script>

<style>
.tools .clear {
width: 85px;
}
.loader {
background-position: 78px 7px;
background-image: none;
}
.loading .loader {
background-image: url(./i/img/black/loader.gif);
}

</style>

<? Tt::tpl('admin/modules/tags/header', $d) ?>

<? if ($d['itemsDirected']) { ?>
  <div class="info" style="width:600px"><div class="icon"></div>
    Тэги <b>«<?= $d['field']['title'] ?>»</b> управляются записями. Это означает, что 
    создание тэга происходит в момент создания записи в которой присутствует этот тэг. 
    Удаление тэга происходит тогда, когда количество записей с таким тэгом равно нулю.</div>
<? } ?>

<? if ($d['tags']) { ?>
  <? if ($d['pagination']['pNums']) { ?>
    <div class="pNums pNumsTop">
      <?= $d['pagination']['pNums'] ?>
    </div>
  <? } ?>
  <table cellpadding="0" cellspacing="0" id="itemsTable">
  <thead>
  <tr>
    <th>&nbsp;</th>
    <th>Тэг</th>
    <th>Кол-во записей</th>
  </tr>
  </thead>
  <tbody>
  <? foreach ($d['tags'] as $k => $v) { ?>
  <tr id="<?= 'item_'.$v['id'].'_'.$v['oid'] ?>">
    <td class="tools loader">
      <div class="dragBox"></div>
      <a class="iconBtn edit" href="<?= Tt::getPath() ?>?a=edit&id=<?= $v['id'] ?>" title="Редактировать"><i></i></a>
      <a class="iconBtn delete" title="Удалить тэг"
         href="<?= Tt::getPath() ?>?a=deleteTag&id=<?= $v['id'] ?>"
         ><i></i></a>
      <div class="clear"><!-- --></div>
    </td>
    <td><?= $v['title'] ?></td>
    <td><?= (int)$v['cnt'] ?></td>
  </tr>
  <? } ?>
  </tbody>
  </table>
  <? if ($d['pagination']['pNums']) { ?>
    <div class="pNums pNumsTop">
      <?= $d['pagination']['pNums'] ?>
    </div>
  <? } ?>
<? } else { ?>
  <p>Тэги отсутствуют</p>
<? } ?>
