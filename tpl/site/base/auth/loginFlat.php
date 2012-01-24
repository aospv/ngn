<div class="loginFlat">
<div class="body">
<? if ($_AUTH["login"]) { ?>
  <? Tt::tpl('auth/loginPersonal'); ?>
<? } else { ?>
  <form action="<?= Tt::getPath() ?>" method="post" id="loginForm">
  <table cellspacing="0" cellpadding="0" width="100%">
    <tr>
      <td>Логин:</td>
      <td>Пароль:</td>
      <td></td>
    </tr><tr>
      <td><input type="text" name="authLogin" id="userLogin" class="fld" style="width:100%"></td>
      <td><input type="password" name="authPass" id="userPass" class="fld"  style="width:100%"></td>
      <td><a href="#" id="btnLogin" title="Войти" /></a></td>
    </tr>
  </table>
  <table cellspacing="0" cellpadding="0" style="margin-bottom: 3px;">
  <tr>
    <td><input type="checkbox" name="doNotSavePass" id="doNotSavePass" value="1"></td>
    <td width="100%"><label for="doNotSavePass"><small>чужой компьютер</small></label></td>
  </tr>
  </table>
  <div class="links">
    <!-- <a href="<? //Tt::getControllerPath('password') ?>">Забыли пароль?</a>&nbsp;&nbsp;-->
    <a href="<?= Tt::getControllerPath('userReg') ?>">Регистрация</a>
  </div>
  <? if ($_POST['authLogin'] and $_AUTH["msg"]) { ?><div class="alert"><?= $_AUTH["msg"] ?></div><? } ?>
  <script type="text/javascript">
  $('btnLogin').addEvent('click', function(e){ new Event(e).stop(); $('loginForm').submit(); });
  $('userLogin').addEvent('keydown', function(e){ if (e.keyCode==13) { $('loginForm').submit(); }});
  $('userPass').addEvent('keydown', function(e){ if (e.keyCode==13) { $('loginForm').submit(); }});
  </script>
<? } ?>
</div>
</div>