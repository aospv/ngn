<!-- дополнительная - основная - дополнительная -->

<div class="span-5 col" id="col1">
  <div class="body">
    <?= $d['col1'] ?>  
  </div>
</div>
<div class="span-14 col" id="col2">
  <div class="body moduleBody<?= $d['bodyClass'] ?>">
    <div class="bcont">
      <? if (!empty($d['orderMenu'])) { ?>
        <div id="orderMenu" class="submenu hMenu">
          <? Tt::tpl('common/menu-ul', $d['orderMenu']) ?>
        </div>
        <div class="clear"><!-- --></div>
      <? } ?>
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
  </div>
</div>
<div class="span-5 last col" id="col3">
  <div class="body">
    <?= $d['col3'] ?>  
  </div>
</div>
