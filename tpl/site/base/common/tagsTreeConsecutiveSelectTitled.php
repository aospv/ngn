<? if (empty($d['v']) or !count($d['v'])) return; ?>
<b class="title"><?= $d['title'] ?></b>: 
<? foreach ($d['v'] as $v) { ?>
  <a href="<?= $d['pagePath'].'/t2.'.$d['name'].'.'.$v['name'] ?>"><?= $v['title'] ?></a>
<? } ?>