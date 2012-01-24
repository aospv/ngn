<? if (Auth::check()) { ?>
  <? Tt::tpl('auth/loginPersonal'); ?>
<? } else { ?>
  <form action="<?= Tt::getPath() ?>" method="post" name="loginform">
  <table cellspacing="6" cellpadding="6" width="100%">
    <tr>
      <td>Логин</td>
      <td width="100%"><input type="text" name="authLogin" style="width:90%" class="fld"></td>
    </tr><tr>
      <td>Пароль</td>
      <td><input type="password" name="authPass" class="fld"  style="width:90%"></td>
    </tr>
  </table>
  </div>
  <table cellspacing="0" cellpadding="0" style="margin: 7px 0px 7px 20px;">
  <tr>
    <td><input type="submit" value="Войти" class="btn"></td>
    <td style="padding-left:5px;"><input type="checkbox" name="doNotSavePass" id="doNotSavePass" value="1"></td>
    <td style="padding-left:5px;" width="100%"><label for="doNotSavePass"><small>чужой компьютер</small></label></td>
  </tr>
  </table>
  <? if (($msg = Auth::get('msg'))) { ?>
    <p class="alert"><?= $msg ?></p>
  <? } ?>
  </form>
<? } ?>

