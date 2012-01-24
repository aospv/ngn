<? if (!$d) { ?>
  There is no data in notify template
<? } else { $maxN = 5; $n = 0; ?>
  <p><b>Новые сообщения:</b>
  <? foreach ($d as $pageId => $page) { ?>
    <? foreach ($page['items'] as $itemId => &$item) {
         $n++;
         if ($n > $maxN) break 2; ?>
      <p>
        Раздел:
        <a href="<?= $page['data']['path'] ?>" target="_blank"><?= $page['data']['title'] ?></a> / 
        <a href="<?= $page['data']['path'].'/'.$item['data']['id'] ?>" target="_blank"><?= $item['data']['title'] ?></a>
      </p>
      <? foreach ($item['items'] as &$msg) { ?>
        <hr />
        <? if ($msg['userId']) { ?>
          <b><a href="<?= Tt::getUserPath($msg['userId']) ?>"><?= $msg['login'] ?></a></b>:
        <? } else { ?>
          <b><?= $msg['nick'] ?></b>:
        <? } ?>
        <span class="commentText"><?= $msg['text'] ?></span>
        &nbsp;<a href="<?= $page['data']['path'].'/'.$item['data']['id'].'#cmt'.$msg['id'] ?>" title="Перейти к комментарию">→</a>
      <? } ?>
    <? } ?>
  <? } ?>
<? } ?>

<? Tt::tpl('notify/msgs/commentsUnsubscribe') ?>