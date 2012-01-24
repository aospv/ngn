<? if ($_input['lastSubjs']) { ?>
<? if ($_input["pNums"]) { ?>
<div class="pNums pNumsTop"><?= $_input["pNums"] ?><div class="end2"><!-- --></div></div><? } ?>
<div class="itemsList lastForum markedList">
<? foreach ($_input['lastSubjs'] as $k => $v) { ?>
  <div class="item<?= ($v['user_id'] == $_AUTH['id'] ? ' owner' : '') ?>">
    <div class="date"><small><?= date('H:i:s', $v['date2']); ?></small></div>
    <p>
      <b><a href="<?= $v['link'] ?>"><?= $v['title'] ?></a></b>
      <? if ($v['msgs_count']) { ?><small>(<?= $v['msgs_count'] ?>)</small><? } ?>
    </p>
    <div class="cmt">
      <? if ($v['last_msg_text']) { ?>
        <a href="<?= $v['lastUserLink'] ?>"><?= $v['last_login'] ?></a>:
        <a href="<?= $v['link'] ?>#cmt<?= $v['id'] ?>" class="text"><?= cut_length($v['last_msg_text'], 200) ?></a>
      <? } else { ?>
        <a href="<?= $v['userLink'] ?>"><?= $v['login'] ?></a>:
        <a href="<?= $v['link'] ?>#cmt<?= $v['id'] ?>" class="text"><?= cut_length($v['text'], 200) ?></a>
      <? } ?>
    </div>
  </div>
  <div class="end2"><!-- --></div>
<? } ?>
</div>
<? if ($_input["pNums"]) { ?>
<div class="pNums pNumsBottom"><?= $_input["pNums"] ?><div class="end2"><!-- --></div></div><? } ?>
<? } ?>