<?php

$links = array();

$linksCommon[] = array(
  'title' => 'Листы рассылок',
  'class' => 'list',
  'link' => Tt::getPath(2)
);
$linksCommon[] = array(
  'title' => 'Создать лист',
  'class' => 'add',
  'link' => Tt::getPath(2).'/new'
);
if (isset($d['params'][3])) { 
  $links[] = array(
    'title' => 'Разослать',
    'class' => 'subscribe',
    'link' => Tt::getPath(2).'/send/'.$d['params'][3]
  );
  $links[] = array(
    'title' => "Ящики".($d['emailsCnt'] ? ' ('.$d['emailsCnt'].')' : ''),
    'class' => 'email',
    'link' => Tt::getPath(2).'/emails/'.$d['params'][3]
  );
  if ($d['list']['useUsers']) {
    $links[] = array(
      'title' => "Пользователи".($d['usersCnt'] ? ' ('.$d['usersCnt'].')' : ''),
      'class' => 'users',
      'link' => Tt::getPath(2).'/users/'.$d['params'][3]
    );
  }
  /*
  $links[] = array(
    'title' => 'Добавить ящик',
    'class' => 'add',
    'link' => Tt::getPath(2).'/newEmail/'.$d['params'][3]
  );
  */
  $links[] = array(
    'title' => "Добавить ящики",
    'class' => 'import',
    'link' => Tt::getPath(2).'/import/'.$d['params'][3]
  );
  $links[] = array(
    'title' => 'Рассылки',
    'class' => 'list',
    'link' => Tt::getPath(2).'/subs/'.$d['params'][3]
  );
}

?>

<div class="navSub iconsSet" id="subNav">
  <div class="navSubBtns" style="float:left">
  <? foreach ($linksCommon as $v) { ?>
    <a href="<?= $v['link'] ?>" class="<?= $v['class'] ?>"<?= isset($v['target']) ? ' target="'.$v['target'].'"' : '' ?><?= isset($v['descr']) ? 'title="'.$v['descr'].'"' : '' ?>><i></i><?= $v['title'] ?></a>
  <? } ?>
  </div>
  <? if ($d['list']) { ?>
  <div class="navSubTitle">
    Лист «<?= $d['list']['title'] ?>»:
  </div>
  <div class="navSubBtns">
  <? foreach ($links as $v) { ?>
    <a href="<?= $v['link'] ?>" class="<?= $v['class'] ?>"<?= isset($v['target']) ? ' target="'.$v['target'].'"' : '' ?><?= isset($v['descr']) ? 'title="'.$v['descr'].'"' : '' ?>><i></i><?= $v['title'] ?></a>
  <? } ?>
  <div class="clear"><!-- --></div>
  </div>
  <? } ?>
  <div class="clear"><!-- --></div>
</div>
