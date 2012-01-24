<?php if (!$d['v']) return; ?>
<span class="dgray">
  <b class="title"><?= $d['title'] ?>:</b>
  <?= Tt::enumDddd($d['v'], '`<a href="'.$d['pagePath'].'/t2.'.$d['name'].'.`.$id.`">`.$title.`</a>`') ?>
</span>