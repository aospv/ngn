<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" id="vkontakte"> 
<head> 
  <meta http-equiv="content-type" content="text/html; charset=<?= CHARSET ?>" /> 
  <title><?= SITE_TITLE.': '.LANG_ENTER_TO_ADMIN ?></title> 
  <link rel="stylesheet" href="./i/css/common.css" type="text/css" /> 
  <link rel="stylesheet" href="./i/css/admin/main.css" type="text/css" />
  <script type="text/javascript" src="./i/js/mootools/mootools-1.2.2.2.js"></script>
</head> 
 
<body>
<center>
  <div class="loginAdmin">
    <h1><a href="/"><?= SITE_TITLE ?></a> — <?= LANG_ADMINISTRATION ?></h1>
    <? if (Auth::check()) { ?>
      <p>Logged in as <b><?= Auth::get('login') ?></b>.
        <a href="<?= Tt::getPath() ?>?logout=1">Logout</a></p>
      <? if (Misc::isAdmin()) { ?>
        <p><a href="/admin">Admin</a></p>
      <? } ?>
      <p class="error">У вас нет прав доступа к панеле управления сайтом</p>
    <? } else { ?>
      <form action="<?= Tt::getPath() ?>" method="post" name="loginform">
      <table cellspacing="0" cellpadding="2" width="100%">
      <tr>
        <td><?= LANG_LOGIN ?>:</td>
        <td width="100%"><input type="text" name="authLogin" id="authLogin" value="<?= htmlentities($_REQUEST['authLogin']) ?>" style="width:90%" /></td>
      </tr><tr>
        <td><?= LANG_PASSWORD ?>:</td>
        <td><input type="password" name="authPass" id="authPass" style="width:90%"></td>
      </tr>
      </table>  
      <input type="submit" value="<?= LANG_ENTER_TO_ADMIN ?>" />
      </form>
      <? if (Auth::get('msg')) { ?>
        <div class="error"><?= Auth::get('msg') ?></div>
      <? } ?>
    <? } ?>
    <a href="http://asite.ru" target="_blank" class="copy">© aSite.ru — advanced site technologies</a>
  </div>
</center>

<script type="text/javascript">
window.addEvent('domready', function() {
  <? if (Auth::get('msg')) { ?>
  var authPass = $('authPass');
  if (authPass) authPass.focus();
  <? } else { ?>
  var authLogin = $('authLogin');
  if (authLogin) authLogin.focus();
  <? } ?>
});
</script>

</body>
</html>













