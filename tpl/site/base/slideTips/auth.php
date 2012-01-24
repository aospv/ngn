<? ob_start() ?>
  <div id="lp_loader"></div>
  <div id="lp_text">
    <p><?= $d[0]['text'] ?></p>
    <p>
    <? if ($d[0]['code'] == 1) { ?>
      Забыли свой логин?<br /><a href="<?= Tt::getControllerPath('userReg').'/lostpass' ?>">Пройдёмте сюда...</a>
    <? } elseif ($d[0]['code'] == 2) { ?>
      Забыли свой пароль?<br /><a href="<?= Tt::getControllerPath('userReg').'/lostpass' ?>">Воспользуйтесь восстановлением пароля</a>
    <? } else { ?>
      Неизвестная ошибка
      <? pr($d) ?>
    <? } ?>
    </p>
  </div>
<? Tt::tpl('slideTips/common', array(
  'class' => 'topSlideTipAuth',
  'items' => array(ob_get_clean()))
);
?>