О поздравалю тебя, теперь пришло время завести супер-пользователя, доступ и возможности которого будут неограничены. Приступай: вводи логин и пароль. Внимание! E-mail важен! На него буду приходить все уведомления...

<h2>Создание Администратора</h2>

<form action="<?= Tt::getPath() ?>" method="post">
  <table>
  <tr>
    <td>Логин:</td>
    <td><input type="text" name="login" id="login" value="<?= $d['login'] ?>" /></td>
  </tr>
  <tr>
    <td>Пароль:</td>
    <td><input type="password" name="pass" id="pass" value="<?= $d['pass'] ?>" /></td>
  </tr>
  <tr>
    <td>E-mail:</td>
    <td><input type="text" name="email" id="email" value="<?= $d['email'] ?>" /></td>
  </tr>
  </table>
  <input type="button" value="Создать" id="btnCreateAdmin" style="width:200px;height:30px;margin-top:7px" />
</form>

<p class="error" id="error" style="display:none;"><span class="icon"></span><span id="errorText"></span></p>

<script>
$('btnCreateAdmin').addEvent('click', function(e){
  this.setProperty('disabled', true);
  new Request.JSON({
    url: window.location.href + '?a=json_createGod',
    onComplete: function(data) {
      if (!data) {
        alert('Ошибка запроса к серверу');
        $('btnCreateAdmin').setProperty('disabled', false);
        return;
      }
      if (data.error) {
        $('error').setStyle('display', 'block');
        $('errorText').set('text', data.error);
        $('btnCreateAdmin').setProperty('disabled', false);
        return;
      }
      if (data.success) {
        alert('Создание администратора прошло успешно');
        window.location = '/admin';
      }  
    }
  }).POST({
    'login' : $('login').get('value'),
    'pass' : $('pass').get('value'),
    'email' : $('email').get('value')
  });
});
</script>