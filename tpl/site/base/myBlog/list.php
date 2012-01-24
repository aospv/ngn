<? if (!empty($d['items'])) { ?>
  <p><a href="<?= Tt::getPath(1).'/new' ?>">создать ещё один</a>?</p>
<? } else { ?>
  <p>У вас нет ниодного блога. Хотите <a href="<?= Tt::getPath(1).'/new' ?>">создать</a>?</p>
<? } ?>