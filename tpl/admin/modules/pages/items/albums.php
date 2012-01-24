<?php

// @title Редактирование альбомов в админке
/* @var $oDdo Ddo */
$oDdo = O::get('DdoAdminFactory', $d['page'])->get();
$oDdo->setItems($d['items']);

?>

<script type="text/javascript">
window.addEvent('domready', function(){
  new Ngn.Items($('items'), {
    isSorting: <?= $d['page']['settings']['order'] == 'oid' ? 'true' : 'false' ?>
  });
  new Ngn.cp.DdItemsGroup($('items'));
});
</script>

<form method="post" id="itemsForm" method="post">
<div class="module_<?= $d['page']['module'] ?> layoutMode_<?= $d['page']['settings']['ddItemsLayout'] ?>">
  <? Tt::tpl('admin/common/pnums', $d) ?>
  <div class="items" id="items">
  <? foreach ($d['items'] as $v) { ?>
    <!-- Начало цикла вывода полей -->
    <? foreach ($fields as $f) { $a = $v[$f['name']]; ?>
      <div class="item <?= $f['name'] ?><?= $v['active'] ? '' : ' nonActive' ?>" id="<?= 'item_'.$v['id'].'_'.$v['oid'] ?>">
        <?= $oDdo->el($a, $f['name'], $v['id']) ?>
        <div class="tools loading">
          <a class="iconBtn edit" title="<?= LANG_EDIT ?>"
            href="<?= Tt::getPath(5) ?>?a=edit&itemId=<?= $v['id'] ?>"><i></i></a>
          <a class="iconBtn delete" title="<?= LANG_DELETE ?>"
            href="<?= Tt::getPath(5) ?>?a=delete&itemId=<?= $v['id'] ?>"><i></i></a>
          <a class="iconBtn <?= $v['active'] ? 'activate' : 'deactivate' ?>" title="<?= $v['active'] ? LANG_HIDE : LANG_SHOW ?>"
            href="<?= Tt::getPath(5) ?>?a=<?= $v['active'] ? 'deactivate' : 'activate' ?>&itemId=<?= $v['id'] ?>"><i></i></a>
          <a class="iconBtn link" target="_blank" title="<?= LANG_OPEN_ENTRY_PAGE_ON_SITE ?>"
            href="/<?= $v['pagePath'].'/'.$v['id'] ?>"><i></i></a>
          <? if ($d['canMove']) { ?>
            <a class="iconBtn move" title="<?= LANG_MOVE ?>"
              href="<?= Tt::getPath() ?>?a=moveForm&itemId=<?= $v['id'] ?>"><i></i></a>
          <? } ?>
          <input type="checkbox" name="itemIds[]" value="<?= $v['id'] ?>" />
          <div class="clear"><!-- --></div>
        </div>
        
      </div>
    <? } ?>
    <!-- Конец цикла вывода полей -->
  <? } ?>
  </div>
  <? Tt::tpl('admin/common/pnums', $d) ?>
</div>
</form>
  