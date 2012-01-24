<ul>
<? foreach ($d['items'] as $name => $v) { ?>
  <li><a href="<?= Tt::getPath(2).'/flash/'.$name ?>"><?= $v['title'] ?></a></li>
<? } ?>
</ul>