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
    <?= $d['col1'] ?>
  </div>
</div>
<div class="span-14 col" id="col2">
  <div class="body moduleBody<?= $d['bodyClass'] ?>">
    <div class="bcont">
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
