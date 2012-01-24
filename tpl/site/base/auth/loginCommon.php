<? if (Auth::check()) { ?>
  <p>Logged in as <b><?= Auth::get('login') ?></b>.
  <a href="<?= Tt::getPath() ?>?logout=1">Logout</a></p>
  <? if (Misc::isAdmin()) { ?>
    <p><a href="/admin">Admin</a></p>
  <? } ?>
<? } else { ?>
  <form action="<?= Tt::getPath() ?>" method="post" name="loginform">
  <table cellspacing="0" cellpadding="2" width="100%">
    <tr>
      <td>Логин:</td>
      <td width="100%"><input type="text" name="authLogin" id="authLogin" style="width:90%" /></td>
    </tr><tr>
      <td>Пароль:</td>
      <td><input type="password" name="authPass" id="authPass" style="width:90%"></td>
    </tr>
  </table>  
  <input type="submit" value="Войти" />
  </form>
  <? if ($path = Tt::getControllerPath('userReg')) { ?>
    <a href="<?= $path ?>">Регистрация</a>
  <? } ?>
  <? if (Auth::get('msg')) { ?>
    <div class="error"><?= Auth::get('msg') ?></div>
  <? } ?>
<? } ?>
