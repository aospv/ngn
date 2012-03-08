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
    <?= $d['col2'] ?>
  </div>
</div>
