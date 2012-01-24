<? if (count($d['items']) > 0) { ?>
  <div class="items">
    <? foreach ($d['items'] as $k => $v) { ?>
    <div class='item'>
      <div class="date">
        <?= $v['subjsCount'] ?> ..
        <?= $v['msgsCount']  ?>
      </div>
      <h3><a href="<?= Tt::getPath(1) ?>/<?= $v['id'] ?>"><?= $v['title'] ?></a></h3>
      <?= $v['descript'] ?>
      <? if ($d['priv']['edit']) { ?>
        <a href="<?= Tt::getPath() ?>?a=delete&id=<?= $v['id'] ?>">удал.</a>
      <? } ?>
    </div>
    <? } ?>
  </div>
<? } else { ?>
  В этом разделе нет форумов
<? } ?>