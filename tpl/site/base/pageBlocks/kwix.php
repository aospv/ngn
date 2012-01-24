<?php //prr($d)?>
<link rel="stylesheet" type="text/css" href="/i/css/common/kwix.css" media="screen, projection" />
<script type="text/javascript" src="/i/js/ngn/Ngn.kwix.js"></script>
<script type="text/javascript">
window.addEvent('domready', Ngn.kwix.start);
</script>
<style>
#kwick .kwick {
height: <?= $d['height'] ?>;
}

<? if ($d['titleColor']) { ?>
.kwicks h2 {
color: <?= $d['titleColor'] ?>;
}
<? } ?>
<? if ($d['textColor']) { ?>
.kwicks .text {
color: <?= $d['textColor'] ?>;
}
<? } ?>

<? if ($d['titleSize']) { ?>
.kwicks h2 {
text-size: <?= $d['titleSize'] ?>;
}
<? } ?>
<? if ($d['textSize']) { ?>
.kwicks .text {
text-size: <?= $d['textSize'] ?>;
}
<? } ?>


<? $n=0; foreach ($d['items'] as $v) { $n++; if (empty($v['bg'])) continue; ?>
.kw<?= $n ?> {
background: url(<?= '/'.UPLOAD_DIR.'/'.$v['bg'] ?>);
}
<? } ?>
</style>

<div id="kwick">
  <ul class="kwicks">
  <? $n=0; foreach ($d['items'] as $v) { $n++; ?>
  <li>
    <a class="kwick kw<?= $n ?>" href="<?= $v['link'] ?>">
      <span class="cont">
        <h2><?= $v['title'] ?></h2>
        <? if ($v['text']) { ?><span class="text"><?= $v['text'] ?></span><? } ?>
      </span>
    </a>
  </li>
  <? } ?>
  </ul>
</div>
