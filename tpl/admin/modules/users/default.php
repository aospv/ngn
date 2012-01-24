<? Tt::tpl('admin/modules/users/header', $d) ?>

<? if ($d['pagination']['pNums']) { ?>
  <div class="pNums pNumsTop">
    <?= $d['pagination']['pNums'] ?>
    <div class="clear"><!-- --></div>
  </div>
<? } ?>

<style>
#itemsTable .loader .clear {
width: 115px;
}
#itemsTable .loader {
background-position: 105px 7px;
}
</style>

<script type="text/javascript">
Ngn.UsersItemsTable = new Class({
  Extends: Ngn.ItemsTable,

  options: {
    isSorting: false,
  },

  init: function() {
    this.parent();
    this.addBtnsActions([
      ['a.edit', this.edit.bind(this)],
    ]);
  },

  edit: function(userId, eBtn) {
    new Ngn.Dialog.RequestForm({
      url: eBtn.get('href').replace('edit', 'json_edit'),
      onSubmitSuccess: function() {
        window.location.reload();
      }
    });
  }
  
});

window.addEvent('domready', function(){
  new Ngn.UsersItemsTable({
    isSorting: false
  });
});
</script>

<? $privAllowed = AdminModule::isAllowed('privileges'); ?>
<? if ($d['items']) { ?>
<table cellpadding="0" cellspacing="0" id="itemsTable">
  <thead>
    <tr>
      <th>&nbsp;</th>
      <th>Логин</th>
      <th>Ящик</th>
      <th>Дата регистрации</th>
    </tr>
  </thead>
  <tbody>
  <? foreach ($d['items'] as $k => $v) { ?>
  <tr<?= $v['active'] ? '' : ' class="nonActive"'?> id="<?= 'item_'.$v['id'].'_10'?>">
    <td class="tools loader">
      <a class="iconBtn delete" title="Удалить пользователя"
        href="<?= Tt::getPath() ?>?a=delete&id=<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn edit" title="Редактировать"
        href="<?= Tt::getPath() ?>?a=edit&id=<?= $v['id'] ?>"><i></i></a>
      <? if ($privAllowed) { ?>
      <a class="iconBtn privileges" title="Привилегии"
        href="<?= Tt::getPath(1) ?>/privileges?a=userPrivileges&userId=<?= $v['id'] ?>"><i></i></a>
      <? } ?>
      <a class="iconBtn <?= $v['active'] ? 'activate' : 'deactivate' ?>" title="<?= $v['active'] ? 'Скрыть' : 'Отобразить' ?>"
        href="<?= Tt::getPath() ?>?a=<?= $v['active'] ? 'deactivate' : 'activate' ?>&id=<?= $v['id'] ?>"><i></i></a>
      <!--
      <a class="iconBtn subordination" title="Подчинение"
        href="<? Tt::getPath(1) ?>subordination/<?= $v['id'] ?>"><i></i></a>
      -->
      <div class="clear"></div>
    </td>
    <td><?= $v['login'] ?></td>
    <td><i><?= $v['email'] ?></i>&nbsp;</td>
    <td><?= !$v['dateCreate_tStamp'] ? '<small class="gray">не определена</small>' : '<small>'.datetimeStr($v['dateCreate_tStamp']).'</small>' ?></td>
  </tr>
  <? } ?>
  </tbody>
</table>
<? } ?>

<? if ($d['searchLogin'] and !$d['items']) { ?>
  <p>Ниодного пользователя не найдено</p>
<? } ?>


<? if ($d['pagination']['pNums']) { ?>
  <div class="pNums pNumsBottom">
    <?= $d['pagination']['pNums'] ?>
    <div class="clear"><!-- --></div>
  </div>
<? } ?>
