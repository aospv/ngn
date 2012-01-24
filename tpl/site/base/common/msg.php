<?php

$v =& $d;
if (!isset($v['newMsgClass'])) $v['newMsgClass'] = '';
if (!isset($v['canEdit'])) $v['canEdit'] = false;
$d['userPath'] = Tt::getUserPath($v['userId'], true);

?>
<div class="item<?= $v['active'] ? '' : ' nonActive' ?><?= ($v['userId'] == Auth::get('id') ? ' owner' : '') ?><?= $d['newMsgClass'] ?><?= $d['userPath'] ? '' : ' noAvatars' ?>" id="msg<?= $v['id'] ?>">
  <a name="cmt<?= $v['id'] ?>"></a>
  <? if ($d['userPath']) { ?>
    <?= UsersCore::avatar($v['userId'], $v['login']) ?>
  <? } ?>
  <div class="textd">
    <?
    if ($d['canEdit']) Tt::tpl('editBlocks/editBlockSubAjax', $v);
    ?>
    <div class="author">
      <? if ($v['userId']) { ?>
        <? if ($v['login']) { ?>
          <? if ($d['userPath']) { ?>
            <b><a href="<?= $d['userPath'] ?>"><?= $v['login'] ?></a></b>
          <? } else { ?>
            <b><?= $v['login'] ?></b>
          <? } ?>
        <? } else { ?>
          <b class="gray">удалён</b>
        <? } ?>
      <? } else { ?>
        <b><?= $v['nick'] ?></b>
      <? } ?>
      <? if ($v['ansLogin']) { ?>
        → <a href="<?= Tt::getUserPath($v['ansUserId']) ?>"><i></i><?= $v['ansLogin'] ?></a>
      <? } ?>
    </div>
    <div class="text" id="msgText<?= $v['id'] ?>"><?= $v['text_f'] ?></div>
    <div class="subscript">
      <a href="<?= Tt::getPath() ?>#cmt<?= $v['id'] ?>" title="Ссылка на этот пост" class="gray anchor">
      <small><?= datetimeStr($v['dateCreate_tStamp']) ?></small></a>
      <? if ($v['canCreate'] and $v['login'] and Auth::get('id') != $v['userId']) { ?>
        <a href="#" class="smIcons sm-answer gray" title="Ответить"><i></i>ответить</a>
      <? } ?>
      <? if ($v['showPage']) { ?>
        <p class="gray">Раздел:
          <a href="<?= $v['path'] ?>"><?= $v['pageTitle'] ?></a> / 
          <a href="<?= $v['path'].'/'.$v['id2'] ?>"><?= $v['itemTitle'] ? $v['itemTitle'] : 'Запись' ?></a>
        </p>
      <? } ?>
    </div>
  </div>
  <div class="data" style="display:none"><?= json_encode(array(
    'id' => $v['id'],
    'userId' => $v['userId'],
    'login' => str_replace("'", "\\'", $v['login']),
  ))?></div>
  <div class="clear"><!-- --></div>
</div>
