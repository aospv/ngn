<? if (Auth::check()) { ?>
  <? Tt::tpl('auth/loginPersonal'); ?>
<? } else { ?>
  <style>
  .loginForm .field p {
  margin-bottom: 3px;
  }
  .loginForm .field {
  margin-bottom: 6px;
  }
  </style>
  <form action="<?= Tt::getPath() ?>" method="post" name="loginform" class="loginForm">
  <div class="field">
    <p><?= UserRegCore::getAuthLoginTitle() ?>:</p>
    <input type="text" name="authLogin">
  </div>
  <div class="field">
    <p>Пароль:</p>
    <input type="password" name="authPass">
  </div>
  <table cellspacing="0" cellpadding="0">
  <tr>
    <td><input type="submit" value="Войти" class="btn"></td>
    <td style="padding-left:5px;"><input type="checkbox" name="doNotSavePass" id="doNotSavePass" value="1"></td>
    <td style="padding-left:5px;" width="100%"><label for="doNotSavePass"><small>чужой компьютер</small></label></td>
  </tr>
  </table>
  </form>
  <div class="regNav">
  <? if (($path = Tt::getControllerPath('userReg', true))) { ?>
  <ul>
    <li><a href="<?= $path ?>">Регистрация</a></li>
  </ul>
  <? } ?>
  <? if (($msg = Auth::get('msg'))) { ?>
    <p class="alert"><?= $msg ?></p>
  <? } ?>
  </div>
<? } ?>

