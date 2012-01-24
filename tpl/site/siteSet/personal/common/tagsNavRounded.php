<? if (isset($d['tags'][$d['settings']['tagField']]) and $d['action'] == 'list') { ?>
<div class="roundCorners top"><i class="l"></i><i class="r"></i><div class="clear"></div></div>
<div class="bcont">
<? Tt::tpl('common/tagsNav', $d) ?>
</div>
<div class="roundCorners bottom" style="margin-bottom: 5px"><i class="l"></i><i class="r"></i><div class="clear"></div></div>
<? } ?>