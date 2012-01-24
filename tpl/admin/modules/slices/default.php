<? Tt::tpl('admin/modules/slices/header', $d) ?>

<style>
#itemsTable small {
white-space: nowrap;
}
</style>
<?php

$itemsGlobal = array_filter(
  $d['items'],
  create_function('$v', 'return $v["pageId"] == 0;')
);

$items = array_filter(
  $d['items'],
  create_function('$v', 'return $v["pageId"] != 0;')
);

?>
<div class="cols">
  <? if ($items) { ?>
  <div class="col" style="padding-right:15px">
    <h2>Принадлежащие разделам</h2>
    <table cellpadding="0" cellspacing="0" id="itemsTable">
    <tbody>
    <? foreach ($d['items'] as $v) { if (!$v['pageId']) continue; ?>
      <tr>
        <td class="smIcons">
          <a href="" title="Удалить" style="float:right" class="smIcons sm-delete bordered"><i></i></a>
          <a class="sm-slices tooltip" title="Редактировать"
            href="<?= Tt::getPath(2).'/'.$v['pageId'].'/'.$v['id'] ?>"><i></i><?=$v['title']?></a>
          <div class="clear"><!-- --></div>
          <small class="gray"><?= ($v['pageTitle2'] ? $v['pageTitle2'].' / ' : '').
          $v['pageTitle'] ?><!-- (pageId=<?= $v['pageId'] ?>) --></small>
          
        </td>
      </tr>
    <? } ?>
    </tbody>
    </table>
  </div>
  <? } ?>
  <? if ($itemsGlobal) { ?>
  <div class="col">
    <h2>Глобальные</h2>
    <table cellpadding="0" cellspacing="0" id="itemsTable">
    <tbody>
    <? foreach ($d['items'] as $v) { if ($v['pageId']) continue; ?>
      <tr>
        <td class="tools">
          <a href="<?= Tt::getPath(2).'/0/'.$v['id'].'?a=delete' ?>" title="Удалить" style="float:right" class="smIcons sm-delete bordered confirm"><i></i></a>
          <a class="slices tooltip" title="Редактировать"
            href="<?= Tt::getPath(2).'/0/'.$v['id'] ?>"><i></i><?=$v['title']?></a>
        </td>
      </tr>
    <? } ?>
    </tbody>
    </table>
  </div>
  <? } ?>
</div>