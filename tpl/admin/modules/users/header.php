
<div class="navSub iconsSet">
  <a href="<?= Tt::getPath(2) ?>" class="list"><i></i>Список пользователей</a>
  <a href="<?= Tt::getPath(2) ?>?a=new" class="add"><i></i>Создать пользователя</a>
  <div class="searchBlock">
    <input type="text" name="searchLogin" id="searchLogin" 
      value="<?= $d['searchLogin'] ?>" class="mask"
      title="Введите начало логина пользователя и нажмите Enter" />
    <a href="#" id="userSearchBtn" class="searchBtn" title="Искать"></a>
  </div>
  <div class="clear"><!-- --></div>
</div>
<script type="text/javascript">
var searchUser = function() {
  if (searchLogin.getProperty('value')) {
    window.location = window.location.href.split('?')[0] + 
                      '?a=search&searchLogin=' + 
                      searchLogin.getProperty('value');
  } else {
    alert('Введите начало логина для поиска');
  }
}
var searchLogin = $('searchLogin');
$('userSearchBtn').addEvent('click', function(e){
  e.preventDefault();
  searchUser();
});
searchLogin.addEvent('keydown', function(e){
  if (e.key == 'enter') {
    searchUser();
  }
});
</script>