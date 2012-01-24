<div class="body">
<div class="span-24 last">
  <div class="mainHeader">
    <? Tt::tpl('common/pathNav', $d) ?>
    <? Tt::tpl('common/pageTitle', $d) ?>
  </div>
</div>
<div class="span-14 col">
  <div class="mainBody">
    <? Tt::tpl($d['tpl'], $d) ?>
  </div>
</div>
</div>
<div class="span-10 last col">
  <div class="body">
  <?
  Tt::tpl('common/pageBlocksOneCol', array(
    'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 2, $d['oController'])
  ));
  ?>
  </div>
</div>
