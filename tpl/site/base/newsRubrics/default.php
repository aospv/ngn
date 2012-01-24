<div class="items">
<? foreach ($d['tags'] as $k => $v) { ?>
  <div class="item">
  <a href="<?= Tt::getPath().'/'.$v['name'] ?>"><?= $v['title'] ?></a>
  </div>
<? } ?>
</div>