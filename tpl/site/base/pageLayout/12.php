<div class="span-24 last col">
  <div class="body">
  <?
  Tt::tpl('common/pageBlocksOneCol', array(
    'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 1, $d['oController'])
  ));
  ?>
  </div>
</div>
<div class="span-3 col">
  <div class="body">
  <?
  Tt::tpl('common/pageBlocksOneCol', array(
    'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 2, $d['oController'])
  ));
  ?>
  </div>
</div>
<div class="span-7 col">
  <div class="body">
  <?
  Tt::tpl('common/pageBlocksOneCol', array(
    'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 3, $d['oController'])
  ));
  ?>
  </div>
</div>
<div class="span-7 col">
  <div class="body">
  <?
  Tt::tpl('common/pageBlocksOneCol', array(
    'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 4, $d['oController'])
  ));
  ?>
  </div>
</div>
<div class="span-7 last col">
  <div class="body">
  <?
  Tt::tpl('common/pageBlocksOneCol', array(
    'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 5, $d['oController'])
  ));
  ?>
  </div>
</div>