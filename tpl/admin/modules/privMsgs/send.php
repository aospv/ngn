<? Tt::tpl('admin/modules/privMsgs/header') ?>

<h2><?= LANG_SEND_MESSAGE ?></h2>

<form action="<?= Tt::getPath()?>" method="post">
  <input type="hidden" name="action" value="send" />
  <? if (empty($d['toUser']['id'])) { ?>
    <p><?= LANG_SEND_TO ?>: <small class="gray">(<?= LANG_FIND_USER ?>)</small></p>
    <p><? Tt::tpl('common/autocompleter', array('name' => 'user')) ?></p>
  <? } else { ?>
    <h3>Отправка сообщения пользователю <b><?= $d['toUser']['login'] ?></b></h3>
    <input type="hidden" name="user" value="<?= $d['toUser']['id'] ?>" />
  <? } ?>
  <textarea name="text" style="width:500px;height:200px;"></textarea>
  <p><input type="submit" value="<?= LANG_SEND ?>" style="width:200px;height:30px;" /></p>
</form>