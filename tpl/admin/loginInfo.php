<? if (Auth::get('login')) { ?>
  <?= LANG_LOGGED_AS ?>
  <b>
  <? if (AdminModule::isAllowed('profile')) { ?>
    <a href="<?= Tt::getPath(1).'/profile' ?>" class="smIcons sm-edit"><i></i><?= Auth::get('login') ?></a>
  <? } else { ?>
    <?= Auth::get('login') ?>
  <? } ?>
  </b>
  <? if (!$d['god'] and Misc::isGod()) { ?>
    <div class="mode"><a href="<?= str_replace('/admin', '/god', $_SERVER['REQUEST_URI']) ?>" class="smIcons sm-god"><i></i><?= LANG_SWITCH_GOD_MODE ?></a></div>
  <? } elseif ($d['god']) { ?>
    <div class="mode"><a href="<?= str_replace('/god', '/admin', $_SERVER['REQUEST_URI']) ?>" class="smIcons sm-god"><i></i><?= LANG_SWITCH_ADMIN_MODE ?></a></div>
 <? } ?>
<? } ?>
