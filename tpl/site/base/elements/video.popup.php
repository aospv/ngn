<? if ($d['v'] != '[processing]') { ?>
  <a href="<?= $d['o']->items[$d['id']]['link'] ?>" class="thumb popup">
    <img src="<?= str_replace(`./`, ``, File::reext($d['v'], 'jpg')) ?>"
      title="<?= $d['o']->items[$d['id']]['title'] ?>" />
    <div style="display:none" class="data">
      <?= json_encode(array(
        'params' => array(
          'width' => $d['o']->w,
          'height' => $d['o']->h,
        ),
        'flashvars' => array(
          'file' => str_replace('./', '/', $d['v']),
          'image' => str_replace('./', '/', File::reext($d['v'], 'jpg')),
          'title' => $d['o']->items[$d['id']]['title'],
          'provider' => 'http'
        )
      )) ?>
    </div>
  </a>
<? } else { ?>
  <a href="#" onclick="return false;" class="thumb">
    <img src="/i/img/video-in-progress.gif" />
  </a>
<? } ?>