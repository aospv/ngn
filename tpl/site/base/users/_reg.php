<? Tt::tpl('common/errors', $d['errors']) ?>

<? if ($d['activation']) { ?>
  <div class="info">
    <p>Регистрация на сайте <b><?= SITE_TITLE ?></b> требует активации вашего 
    аккаунта через указанный e-mail.</p>
    <p>Если в течении суток регистрация не будет подтверждена, Ваш аккаунт будет удалён.</p>
  </div>
<? } ?>

<script type="text/javascript">
function swtchPass() {
  if (swtch('passBlock')) {
    $('swtchPassLink').innerHTML = 'Не изменять пароль';
  } else {
    $('swtchPassLink').innerHTML = 'Изменить пароль';
    $('pass').value = '';
  }
}
</script>

<form action="<?= Tt::getPath() ?>" method="POST">
  <input type="hidden" name="action" value="<?= $d['auth'] ? 'update' : 'create' ?>" />
  <? if ($d['auth']) { ?>
    <p><a href="#" onclick="swtchPass(); return false;" id="swtchPassLink">Изменить пароль</a></p>
    <div style="display:none;" id="passBlock">
      <p><b>Пароль:</b><br />
      <input type="password" name="pass" id="pass" /></p>
    </div>
  <? } else { ?>
    <p>
      Пароль:<br />
      <input type="password" name="pass" value="<?= $_REQUEST['pass'] ? htmlspecialchars($_REQUEST['pass']) : $d['user']['pass'] ?>" />
    </p>
  <? } ?>  
  <p>
    Логин:<br />
    <input type="text" name="login" value="<?= $_REQUEST['login'] ? htmlspecialchars($_REQUEST['login']) : $d['user']['login'] ?>" class="large" />
    <script type="text/javascript">$('login').focus();</script>
  </p>
  <p>
    E-mail:<br />
    <input type="text" name="email" value="<?= $_REQUEST['email'] ? htmlspecialchars($_REQUEST['email']) : $d['user']['email'] ?>" style="width:200px" />
  </p>
  <p class="padTop">
  <? if ($d['auth']) { ?>
    <input type="submit" value="Сохранить" />
  <? } else { ?>
    <input type="submit" value="Регистрация" class="large" />
  <? } ?>
  </p>
</form>
