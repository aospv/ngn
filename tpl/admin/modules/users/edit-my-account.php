<script>
function swtchPass() {
  if (swtch('passBlock')) {
    $('swtchPassLink').innerHTML = '<?= LANG_DO_NOT_CHANGE_PASS ?>';
  } else {
    $('swtchPassLink').innerHTML = '<?= LANG_CHANGE_PASS ?>';
    $('pass').set('value', '');
  }
}

window.addEvent('domready', function(){
  var genPass = $('genPass');
  if (genPass) { 
    genPass.addEvent('click', function(){
      $('pass').set('value', Ngn.randString(6));
    });
  }
});
</script>
<style>
#login {
width: 300px;
}
#pass, #login {
font-size: 17px;
}
</style>
<? $u = $d['user'] ?>
<form action="<?= Tt::getPath() ?>" method="POST">
  <div class="col" style="width:500px">
    <input type="hidden" name="action" value="<?= $d['action'] == 'new' ? 'create' : 'update' ?>" />
    <input type="hidden" name="id" value="<?= $u['id'] ?>" />
    <input type="hidden" name="referer" value="<?= $_SERVER['HTTP_REFERER'] ?>" />
    <? if ($u['complete']) { ?>
      <div class="info"><i></i>Данные изменены успешно</div>
    <? } ?>
    <? if ($d['action'] != 'new') { ?>
    <p><a href="#" onclick="swtchPass(); return false;" id="swtchPassLink"><?= LANG_CHANGE_PASS ?></a></p>
    <div style="display:none;" id="passBlock">
      <p>
        <b><?= LANG_PASSWORD ?>:</b><br />
        <input type="text" name="pass" id="pass" />
      </p>
    </div>
    <? } ?>
    <p><b><?= LANG_LOGIN ?>:</b><br />
      <input type="text" name="login" id="login" value="<?= $u['login']?>" /></p>
    <? if ($d['action'] == 'new') { ?>
      <p>
        <b><?= LANG_PASSWORD ?>:</b> (пароль отображается в открытом виде!)<br />
        <input type="text" name="pass" id="pass" />
        <input type="button" value="Сгенерировать пароль" id="genPass" />
      </p>
    <? } ?>
    <p><b><?= LANG_EMAIL ?>:</b><br />
    <input type="text" name="email" value="<?= $u['email']?>" style="width:200px" /></p>
    <input type="submit" value="<?= $d['action'] == 'new' ? LANG_CREATE : LANG_SAVE ?>" style="width:150px;height:30px;" />
  </div>
  <div class="col">
    <? if ($d['action'] == 'edit') { ?>
    <p>
    <? if ($u['dateCreate_tStamp']) { ?>
      <b>Создан:</b><br /><?= datetimeStr($u['dateCreate_tStamp']) ?>
    <? } else { ?>
      Нет информации о дате создания
    <? } ?>
    </p>
    <p>
    <? if ($u['dateCreate_tStamp']) { ?>
      <b>Изменён:</b><br /><?= datetimeStr($u['dateUpdate_tStamp']) ?>
    <? } else { ?>
      Нет информации о дате изменения
    <? } ?>
    </p>
    <?php /*
    <p>
    <? if ($u['lastTime_tStamp']) { ?>
      <b>Последний визит:</b><br /><?= datetimeStr($u['lastTime_tStamp']) ?>
    <? } else { ?>
      Нет информации о последнем визите
    <? } ?>
    </p>
    */?>
    <? } ?>
  </div>
</form>