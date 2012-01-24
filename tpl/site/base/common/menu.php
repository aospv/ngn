<ul>
<? foreach ($d['items'] as $v) { ?>
  <? if ($d['curLink'] == $v['link']) { ?>
    <li<?= $v['name'] ? ' class="'.$v['name'].'"' : '' ?>><b><a href="<?= $v['link'] ?>"><span><?= $v['title'] ?></span></a></b></li>
  <? } else { ?>
    <li<?= $v['name'] ? ' class="'.$v['name'].'"' : '' ?>><a href="<?= $v['link'] ?>"><span><?= $v['title'] ?></span></a></li>
  <? } ?>
<? } ?>
</ul>
<div class="clear"><!-- --></div>