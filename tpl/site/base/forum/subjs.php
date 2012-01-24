<? if ($d['priv']['sub_create']) { ?>
  <a href="?a=new">Создать тему</a>
<? } ?>
<div class="forum">
<div class="items">
<? foreach ($d['items'] as $k => $v) { ?>
  <div class="item<?= $v['active'] ? '' : ' nonActive'?>">
    <? if ($d['priv']['sub_edit'] or $v['canEdit']) Tt::tpl('editBlocks/editBlockSub', $v) ?>
      <h3><b><a href="<?= Tt::getPath(1).'/'.$v['forumId'].'/'.$v['id'] ?>"><?= $v['title'] ?></a></b></h3>
      <div style="float:right" class="gray"><small>(<b><?= $v['msgsCount'] ?></b>)</small></div>
      <p><?= $v['text'] ?></p>
      <p>Автор: <a href="<?= Tt::getUserPath($v['userId']) ?>"><?= $v['login'] ?></a></p>
  </div>
<? } ?>
</div>
</div>
