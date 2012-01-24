<?php

// @title Cписок записей

if ($d['action'] == 'new' and $d['actionDisallow']) { ?>
<div class="error">
  Для добавления своих текстов вам необходимо авторизоваться на сайте.<br />
  Если вы не <a href="<?= Tt::getControllerPath('userReg') ?>">зарегистрированы</a>, <a href="<?= Tt::getControllerPath('userReg') ?>">сделайте это</a>.
</div>
<? } ?>
<!-- Конец информационных блоков -->

<?php

if ($d['settings']['showFormOnDefault']) {
  Tt::tpl('dd/edit', $d);
}

print Slice::html(
  'beforeDdItems_'.$d['listSlicesId'],
  'Блок перед записями «'.$d['page']['title'].
    (empty($d['listSlicesTitle']) ? '' : ' / '.$d['listSlicesTitle']).'»'
);

Err::noticeSwitch(true);

if (empty($d['settings']['doNotShowItems'])) {
  if (!empty($d['itemsHtml'])) {
    print $d['itemsHtml'].'<div class="clear"><!-- --></div>';
  } else {
    print Slice::html('noItems_'.$d['page']['id'], 'Нет записей', array('default' => 'Нет записей'));
  }
}
