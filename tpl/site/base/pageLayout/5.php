<!-- основная - дополнительная - дополнительная -->

<div class="span-14 col" id="col2">
  <div class="body moduleBody<?= $d['bodyClass'] ?>">
    <div class="roundCorners top"><i class="l"></i><i class="r"></i><div class="clear"></div></div>
    <div class="bcont">
      <? if (!empty($d['submenu'])) { ?>
        <div id="submenu" class="submenu">
          <? Tt::tpl('common/menu-ul', $d['submenu']) ?>
        </div>
        <div class="clear"><!-- --></div>
      <? } ?>
      <div class="mainHeader">
        <? Tt::tpl('common/pathNav', $d) ?>
        <? Tt::tpl('common/pageTitle', $d) ?>
      </div>
      <div class="mainBody">
        <? if ($d['page']['settings']['showSubPages']) print '<div class="subPages">'.Menu::getUlObjById($d['page']['id'], 1)->html().'</div>' ?>
        <? Tt::tpl($d['tpl'], $d) ?>
      </div>
    </div>
    <div class="roundCorners bottom"><i class="l"></i><i class="r"></i><div class="clear"></div></div>
  </div>
</div>
<div class="span-5 col" id="col1">
  <div class="body">
    <?
    Tt::tpl('common/pageBlocksOneCol', array(
      'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 2, $d['oController'])
    ));
    ?>
  </div>
</div>
<div class="span-5 last col" id="col3">
  <div class="body">
    <?
    Tt::tpl('common/pageBlocksOneCol', array(
      'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 3, $d['oController'])
    ));
    ?>
  </div>
</div>
