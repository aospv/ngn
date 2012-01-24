<?php // pr($d) ?>
<div class="forum">
  <p>Автор: <a href="<?= Tt::getUserPath($d['subj']['userId']) ?>"><?= $d['subj']['login'] ?></a></p>
  <p class="subj large<?= $d['active'] ? '' : ' nonActive' ?>">
    <? if ($d['priv']['edit']) Tt::tpl('editBlocks/editBlockSub', array(
      'id' => $d['subj']['id'],
      'active' => $d['subj']['active'],
    )) ?>
    <i><?= $d['subj']['text_f'] ?></i>
  </p>
  <div class="comments">
    <b class="title">Сообщения</b> <sup class="count">(<?= count($d['items']) ?>)</sup>
    <? Tt::tpl('common/msgs', $d) ?>
  </div>
</div>