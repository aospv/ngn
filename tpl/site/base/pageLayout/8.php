<div class="span-12 col">
  <div class="body">
  <?
  Tt::tpl('common/pageBlocksOneCol', array(
    'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 1, $d['oController'])
  ));
  ?>
  </div>
</div>
<div class="span-12 last col">
  <div class="body">
  <?
  Tt::tpl('common/pageBlocksOneCol', array(
    'blocks' => PageBlockCore::getBlocksByCol($d['page']['id'], 2, $d['oController'])
  ));
  ?>
  </div>
</div>
