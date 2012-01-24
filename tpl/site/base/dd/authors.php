<div class="usersFull">
<? foreach ($d['items'] as $v) { ?>
  <li>
    <div class="avatar">
      <a href="<?= Tt::getUserPath($v['id']) ?>" title="<?= $v['login'] ?>">
      <img src="<?= is_file(WEBROOT_PATH.'/'.$v['sm_image']) ? '/'.$v['sm_image'] : '/m/img/no-avatar.gif' ?>" /></a>
    </div>  
    <p style="line-height: 1.2em"><a href="<?= Tt::getPath(1).'/u.'.$v['id'] ?>"><?= $v['login'] ?></a></p>
    <? if ($v['dateCreate_tStamp']) { ?>
      <small class="gray">Зарегистрирован: <?= dateStr($v['dateCreate_tStamp']) ?></small>
    <? } ?>
  </li>
<? } ?>
<div class="end"><!-- --></div>
</div>