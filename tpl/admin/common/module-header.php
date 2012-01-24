<? if (!empty($d['links'])) { ?>
<div class="navSub iconsSet" id="subNav">
  <? if (!empty($d['title'])) { ?>
    <div class="navSubTitle"><?= $d['title'] ?></div>
  <? } ?>
  <div class="navSubBtns">
    <? Tt::tpl('cp/links', $d['links']) ?>
    <div class="clear"><!-- --></div>
  </div>
</div>
<? } ?>