<? if (!$d['installed']) { ?>
Приветствуем вас на нашем инсталляционном экране. Вы были посажены сюда и 
хрен теперь выберетесь. Вот и посмотрим как вы протяните здесь, скажем, 
больше месяца... Да какой там месяц, протяните тут хотя бы 4 дня и я 
дам вам конфетку. Ю а велкам!
<? } else { ?>
<div class="error"><span class="icon"></span><b>Внимаение!</b> База данных уже была установлена</div>
<? } ?>
<h2>Настройки базы данных</h2>

<form action="<?= Tt::getPath() ?>" method="post">
  <table>
  <tr>
    <td>Хост:</td>
    <td><input type="text" name="host" id="host" value="<?= $d['host'] ?>" /></td>
  </tr>
  <tr>
    <td>Пользователь:</td>
    <td><input type="text" name="user" id="user" value="<?= $d['user'] ?>" /></td>
  </tr>
  <tr>
    <td>Пароль:</td>
    <td><input type="password" name="pass" id="pass" value="<?= $d['pass'] ?>" /></td>
  </tr>
  <tr>
    <td>Имя базы данных:</td>
    <td><input type="text" name="name" id="name" value="<?= $d['name'] ?>" /></td>
  </tr>
  </table>
  <input type="button" value="Установить" id="btnInstallDb" class="submit" style="width:200px;height:30px;margin-top:7px" />
</form>

<p class="error" id="error" style="display:none;"><span class="icon"></span><span id="errorText"></span></p>

<script>
$('btnInstallDb').addEvent('click', function(e){
  this.setProperty('disabled', true);
  new Request.JSON({
    url: window.location.href + '?a=json_installDb',
    onComplete: function(data) {
      if (!data) {
        alert('Ошибка запроса к серверу');
        $('btnInstallDb').setProperty('disabled', false);
        return;
      }
      if (data.error) {
        $('error').setStyle('display', 'block');
        $('errorText').set('text', data.error);
        $('btnInstallDb').setProperty('disabled', false);
        return;
      }
      if (data.success) {
        alert('Установка базы данных прошла успешно');
        window.location = '/c/install';
      }  
    }
  }).POST({
    'host' : $('host').get('value'),
    'name' : $('name').get('value'),
    'user' : $('user').get('value'),
    'pass' : $('pass').get('value')
  });
});
</script>