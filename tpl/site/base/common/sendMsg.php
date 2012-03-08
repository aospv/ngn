<div class="sendMsg">
<form action="<?= Tt::getPath()?>" method="post" id="msgForm">
  <input type="hidden" name="action" value="<?= $d['postAction'] ?>" />
  <? if (!empty($d['toUser'])) { ?>
    Сообщение пользователю <a href="<?= Tt::getUserPath($d['toUser']['id'])?>"><?= $d['toUser']['login'] ?></a>:<br />
    <input type="hidden" name="user" value="<?= $d['toUser']['id'] ?>" />
  <? } else { ?>
    <p><?= LANG_SEND_TO ?>: <small class="gray">(<?= LANG_FIND_USER ?>)</small></p>
    <p><? Tt::tpl('common/autocompleter', array('name' => 'user')) ?></p>
  Текст сообщения:<br>
  <? } ?>
  <textarea name="text" id="msgText"></textarea>
  <p><a href="" class="btn btnSubmit btnSubmitLarge" title="(Ctrl+Enter)"><span>Отправить (Ctrl + Enter)</span></a><div class="clear"><!-- --></div></p>
</form>
<script type="text/javascript">
$('msgText').addEvent('keydown', function(e){
  if (e.key == 'enter' && e.control) {
    $('msgForm').submit();
  }
});
$('msgForm').getElement('.btnSubmit').addEvent('click', function(e){
  e.preventDefault();
  $('msgForm').submit();
});
</script>
</div>