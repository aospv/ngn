<!-- дополнительная - основная - дополнительная -->

<div class="span-5 col">
  <div class="body">&nbsp;</div>
</div>
<div class="span-19 last col">
  <div class="mainHeader">
    <? Tt::tpl('common/pathNav', $d) ?>
    <? Tt::tpl('common/pageTitle', $d) ?>
  </div>
</div>

<div class="clear span-5 col" id="col1">
  <div class="body">
  <?
  Tt::tpl('common/pageBlocksOneCol', array(
    'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 1, $d['oController'])
  ));
  ?>
  </div>
</div>
<div class="span-14 col" id="col2">
  <div class="body moduleBody<?= $d['bodyClass'] ?>">
    <div class="roundCorners top"><i class="l"></i><i class="r"></i><div class="clear"></div></div>
    <div class="bcont">
      <div class="mainBody">
        <? if ($d['page']['settings']['showSubPages']) print '<div class="subPages">'.Menu::getUlObjById($d['page']['id'], 1)->html().'</div>' ?>
        <? Tt::tpl($d['tpl'], $d) ?>
      </div>
    </div>
    <div class="roundCorners bottom"><i class="l"></i><i class="r"></i><div class="clear"></div></div>
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
