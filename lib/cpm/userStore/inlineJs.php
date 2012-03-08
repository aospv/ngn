<?php

if (!SiteLayout::topEnabled()) return;
if (!($userId = $d['oController']->userId)) return;

?>

<? if (Config::getVarVar('userStore', 'enable') and UserStoreCore::allowed($userId)) { ?>
  <?
  $newStore = false;
  if (DbModelCore::get('userStoreSettings', $userId) === false) {
    $btnTitle = 'Создать магазин';
    $dialogTitle = 'Создание магазина';
    $newStore = true;
  } else {
    $dialogTitle = $btnTitle = 'Параметры магазина';
  }
  ?>
  Ngn.site.top.briefcase.btns.push(['cart', '<?= $btnTitle ?>', function() {
    new Ngn.Dialog.RequestForm({
      title: '<?= $dialogTitle ?>',
      url: '/c/userStore/json_settings',
      onSubmitSuccess: function() {
        window.location.reload(true);
      }
    });
  }]);
  <? if (!$newStore and ($cnt = DbModelCore::count('userStoreOrder', DbCond::get()->addF('userId', $userId))) !== false) { ?>
  Ngn.site.top.briefcase.btns.push(['list', 'Мои заказы (<?= $cnt ?>)', function() {
    window.location = 'userStoreMyOrders';
  }]);
  <? } ?>
<? } ?>
