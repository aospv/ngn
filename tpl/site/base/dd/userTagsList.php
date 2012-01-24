123
<? foreach ($d['v'] as $v) { ?>
  <a href="<?= $d['pagePath'].'/u.'.$d['authorId'].'/t2.'.$d['name'].'.'.$v['name'] ?>"><?= $v['title'] ?></a>
<? } ?>