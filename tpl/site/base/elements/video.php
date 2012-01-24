<? if ($d['v'] != '[processing]') { ?>
  <a href="<?= $d['o']->items[$d['id']]['link'] ?>" class="thumb">
    <img src="<?= str_replace(`./`, ``, File::reext($d['v'], 'jpg')) ?>"
      title="<?= $d['o']->items[$d['id']]['title'] ?>" />
  </a>
<? } else { ?>
  <a href="#" onclick="return false;" class="thumb">
    <img src="/i/img/video-in-progress.gif" />
  </a>
<? } ?>