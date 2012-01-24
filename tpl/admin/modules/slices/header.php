<?php

$links[] = array(
  'title' => 'Слайсы',
  'class' => 'list',
  'link' => Tt::getPath(2)
);

/*
if ($d['params'][0] == 'god') {
  $links[] = array(
    'title' => 'Создать глобальный слайс',
    'class' => 'add',
    'link' => Tt::getPath(3).'/new'
  );
}
*/

if ($d['params'][0] == 'god' and isset($d['slice'])) {
  $links[] = array(
    'title' => 'Удалить этот слайс',
    'class' => 'delete confirm',
    'link' => Tt::getPath(4).'?a=delete'
  );
}
?>

<div class="navSub iconsSet" id="subNav">
  <div class="navSubBtns"">
  <? foreach ($links as $v) { ?>
    <a href="<?= $v['link'] ?>" class="tooltip <?= $v['class'] ?>"<?= isset($v['target']) ? ' target="'.$v['target'].'"' : '' ?><?= isset($v['descr']) ? 'title="'.$v['descr'].'"' : '' ?>><i></i><?= $v['title'] ?></a>
  <? } ?>
  <div class="clear"><!-- --></div>
  </div>
</div>
