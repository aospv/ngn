<? if ($_input['lastSubjs']) { ?>
<? if ($_input["pNums"]) { ?>
<div class="pNums pNumsTop"><?= $_input["pNums"] ?><div class="end2"><!-- --></div></div><? } ?>
<div class="itemsList markedList">
<? foreach ($_input['lastSubjs'] as $k => $v) { ?>
  <div class="item<?= ($v['user_id'] == $_AUTH['id'] ? ' owner' : '') ?>">
    <? if (1 and $v['last_msg_text']) { ?>
    <div class="rightBlock">
      <small><p><a href="<?= $v['lastUserLink'] ?>"><?= $v['last_login'] ?></a>:</p></small>
      <a href="<?= $v['link'] ?>#cmt<?= $v['id'] ?>" class="gray"><?= cut_length($v['last_msg_text'], 100) ?></a>
    </div>
    <? } ?>
    <p>
      <b><a href="<?= $v['link'] ?>"><?= $v['title'] ?></a></b>
      <small><? if ($v['msgs_count']) { ?>(<?= $v['msgs_count'] ?>)<? } ?>
      (<a href="<?= $v['userLink'] ?>"><?= $v['login'] ?></a>)</small>
    </p>
    <span class="text"><?= cut_length($v['text_f'], 300) ?></span>    
    <div class="subscript">
      <div class="date"><small><?= date('H:i:s', $v['date2']); ?></small></div>
      �����: <a href="<?= $v['forumLink'] ?>"><?= $v['forum_title'] ?></a>
    </div>
    <div class="end2"><!-- --></div>
  </div>
<? } ?>
</div>
<? if ($_input["pNums"]) { ?>
<div class="pNums pNumsBottom"><?= $_input["pNums"] ?><div class="end2"><!-- --></div></div><? } ?>
<? } ?>