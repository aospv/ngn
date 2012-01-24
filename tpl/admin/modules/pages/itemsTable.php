<? if (!$d['items']) { ?>
  <div class="info" style="margin-top: 10px;"><i></i>В каталоге нет разделов</div>
<? } else { ?>
<style>
.tools .clear {
width: 183px;
}
#itemsTable .loader {
background-position: 175px 7px;
}
</style>
<table cellpadding="0" cellspacing="0" id="itemsTable">
<tbody>
<? foreach ($d['items'] as $k => $v) { ?>
  <? $onMenu = $v['onMenu'] ? '' : ' offMenu'; ?>
  <? $isLock = $v['isLock'] ? ' isLock' : ''; ?>
  <tr class="<?= $v['module'] ? 'item-pm-'.$v['module'] : '' ?><?= ($v['active'] ? '' : ' nonActive').$onMenu.$isLock ?>"
  id="<?= 'item_'.$v['id'] ?>">
    <td class="tools">
      <div class="dragBox tooltip" title="Схватить и перетащить"></div>
      <? if ($v['editebleContent']) { ?>
      <a class="iconBtn edit tooltip" title="<?= LANG_EDIT_CONTENT ?>"
        href="<?= Tt::getPath(2).'/'.$v['id'] ?>/editContent"><i></i></a>
      <? } ?>
      <?
      if (($blocksCount = PageBlockCore::getDynamicBlocksCount($v['id']))) { ?>
      <a class="pageBlocks tooltip" title="Редактировать блоки"
        href="<?= Tt::getPath(1).'/pageBlocks/'.$v['id'] ?>"><i></i><?= $blocksCount ?></a>
      <? } ?>
    </td>
    <td class="tools loader">
      <? if ($privAllowed) { ?>
      <a class="iconBtn privileges" title="<?= LANG_PRIVILEGES ?>"
        href="<?= Tt::getPath(1).'/privileges/'.$v['id'].'/pagePrivileges' ?>"><i></i></a>
      <? } ?>
      <? if (!$v['virtual']) { ?>
      <a class="iconBtn editProp" title="<?= LANG_EDIT_PROPERTIES ?>"
        href="<?= Tt::getPath(2).'/'.$v['id'] ?>/editPage"><i></i></a>
      <? if ($d['god']) { ?>
        <? if ($v['controller']) { ?>
          <a class="iconBtn editOptions" title="<?= LANG_CONTROLLER_OPTIONS ?>"
            href="<?= Tt::getPath(2).'/'.$v['id'] ?>/editControllerSettings"><i></i></a>
        <? } else { ?>
          <div class="dummyIconBtn"></div>
        <? } ?>
      <? } ?>
      <a class="iconBtn delete" title="<?= LANG_DELETE_SECTION ?>"
        href="<?= Tt::getPath(2) ?>?a=deletePage&id=<?= $v['id'] ?>"
        onclic="if (confirm('<?= LANG_ARE_YOU_SURE_DELETE_SECTION ?> «<?= $v['title'] ?>» <?= LANG_ALL_PAGES_INSIDE ?>?')) window.location = this.href; return false;"><i></i></a>
      
      <a class="iconBtn <?= $v['active'] ? 'activate' : 'deactivate' ?>" title="<?= $v['active'] ? LANG_HIDE : LANG_SHOW ?>"
        href="<?= Tt::getPath(3) ?>?a=<?= ($v['active'] ? 'deactivate' : 'activate') . '&id='.$v['id'] ?>"><i></i></a>
      <?php /*
      <a class="iconBtn move" title="<?= LANG_MOVE ?>"
        href="<?= Tt::getPath(3) ?>?a=moveForm&id=<?= $v['id'] ?>"><i></i></a>
      <? if ($v['editebleContent']) { ?>        
        <a class="iconBtn <?= $d['favorits'][$v['id']] ? 'favorit' : 'favoritOff'?>" title="<?= LANG_ADD_ANCHOR ?>"
          href="<?= Tt::getPath(3).'/'.($d['favorits'][$v['id']] ? '?a=deleteFavorit&id='.$v['id'] : '?a=addFavorit') ?>"><i></i></a>
      <? } else { ?>
        <div class="dummyIconBtn"></div>
      <? } ?>
      */?>
      <a class="iconBtn <?= $v['onMenu'] ? 'sitemap' : 'sitemapRed' ?>" title="<?= $v['onMenu'] ? LANG_SHOW_IN_MENU : LANG_NOT_SHOW_IN_MENU ?>"
        href="<?= Tt::getPath(3) ?>?a=onMenu&onMenu=<?= $v['onMenu'] ? 0 : 1 ?>&id=<?= $v['id'] ?>"><i></i></a>
      <? } ?>
      <div class="clear"><!-- --></div>
    </td>
      <?php
      if ($v['home']) 
        $itemIconClass = 'home';
      elseif ($v['link'])
        $itemIconClass = 'link';
      elseif ($v['folder'])
        $itemIconClass = 'folder';
      else
        $itemIconClass = 'page';
      ?>
    <td class="tools title"<?= $v['isLock'] ? ' title="<?= LANG_ORANGE_FLAG ?>"' : '' ?>>
      <div class="<?= $itemIconClass ?>">
        <? if ($v['folder']) { ?>
          <a href="<?= Tt::getPath(2).'/'.$v['id'] ?>" class="tooltip" title="Открыть папку">
          <i></i><?= $v['title'] ?></a>
        <? } elseif ($v['editebleContent']) { ?>
          <i></i><a href="<?= Tt::getPath(2).'/'.$v['id'] ?>/editContent" class="tooltip" title="Редактировать содержание"><?= $v['title'] ?></a>
        <? } else { ?>
          <i></i><?= $v['title'] ?>
        <? } ?>
      </div>
    </td>
    <td>
      <a href="<?= Tt::getPath(0).'/'.$v['path'] ?>" target="_blank" class="smIcons sm-link gray" 
        title="<?= LANG_GO_TO_PAGE ?>"><i></i>
      <small><?= Misc::cut($v['path'], 20) ?></small></a>
      <? if ($v['link']) { ?>
        <div style="float:left; margin-right:10px; color: #999999">→</div>
        <a href="<?= $v['link'] ?>" target="_blank" class="smIcons sm-link gray" 
          title="<?= LANG_REDIRECT ?>"><i></i>
        <small><?= Misc::cut($v['link'], 50) ?></small></a>
      <? } ?>
    </td>
    <? if (Misc::isGod()) { ?>
    <td>
      <? if ($v['controller']) { ?>
        <small class="gray"><span title="Контроллер" class="tooltip">{<?= $v['controller'].($v['module'] ? '</span>.<span title="Модуль" class="tooltip">'.$v['module'].'</span>' : '') ?>}</small>
      <? } ?>
      &nbsp;
    </td>
    <? } ?>
  </tr>
<? } ?>
</tbody>
</table>
<? } ?>
