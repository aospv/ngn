<?
if (!isset(Ddo::$g['ddTagsGroupN'])) Ddo::$g['ddTagsGroupN'] = array();
if (!isset(Ddo::$g['ddTagsGroupN'][$d['name']])) {
  $cnt = count(Ddo::$g['ddTagsGroupN']);
  $iconPostfix = $cnt ? $cnt+1 : '';
  Ddo::$g['ddTagsGroupN'][$d['name']] = true;
}
?>
<? if ($d['v']) { ?>
<div class="smIcons">
<b class="title" style="display:block;margin-bottom:3px;"><?= $d['title'] ?>:</b>
<? foreach ($d['v'] as $v) { ?>
  <a href="<?= $d['pagePath'].'/t2.'.$d['name'].'.'.$v['name'] ?>" class="sm-tag<?= $iconPostfix ?>"><i></i><?= $v['title'] ?></a>
<? } ?>
<div class="clear"><!-- --></div>
</div>
<? } ?>