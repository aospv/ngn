<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" id="vkontakte">

<head> 
  <title><?= SITE_TITLE.': '.LANG_ENTER_TO_ADMIN ?></title>
  <? Tt::tpl('admin/headers', $d) ?>
</head> 
 
<body>
<center>
  <div class="loginAdmin">
    <h2><nobr><a href="<?= Tt::getPath(0) ?>"><?= SITE_TITLE ?></a> — <?= LANG_ADMINISTRATION ?></nobr></h2>
    <? if (Auth::check()) { ?>
      <p>Logged in as <b title="id=<?= Auth::get('id') ?>"><?= Auth::get('login') ?></b>.
        <a href="<?= Tt::getPath() ?>?logout=1">Logout</a></p>
      <? if (Misc::isAdmin()) { ?>
        <p><a href="/admin">Admin</a></p>
      <? } ?>
      <p class="error">У вас нет прав доступа к панеле управления сайтом</p>
    <? } else { ?>
      <form action="/admin?a=cleanup" method="post" name="loginform">
      <table cellspacing="0" cellpadding="2" width="100%">
      <tr>
        <td><?= LANG_LOGIN ?>:</td>
        <td width="100%"><input type="text" name="authLogin" id="authLogin" value="<?= isset($_REQUEST['authLogin']) ? htmlentities($_REQUEST['authLogin']) : '' ?>" style="width:90%" /></td>
      </tr><tr>
        <td><?= LANG_PASSWORD ?>:</td>
        <td><input type="password" name="authPass" id="authPass" style="width:90%"></td>
      </tr>
      </table>  
      <input type="submit" value="<?= LANG_ENTER_TO_ADMIN ?>" />
      </form>
      <? if ($d['msg']) { ?>
        <div class="error"><?= $d['msg'] ?></div>
      <? } ?>
    <? } ?>
    <div class="copy"><small><?= AdminCore::getCopyright() ?></small></div>
  </div>
</center>

<script type="text/javascript">
<? if ($d['msg']) { ?>
  var authPass = document.getElementById('authPass');
  if (authPass) authPass.focus();
<? } else { ?>
  var authLogin = document.getElementById('authLogin');
  if (authLogin) authLogin.focus();
<? } ?>
</script>

</body>
</html>