<div class="navSub iconsSet">
  <a href="<?= Tt::getPath(2) ?>" class="list"><i></i><?= LANG_MESSAGES ?></a>
  <a href="<?= Tt::getPath(2) ?>?a=clear" class="delete"
    onclick="if (confirm('<?= LANG_DELETE_MSGS_CONFIRM ?>')) window.location = this.href; return false;"><i></i><?= LANG_DELETE_ALL ?></a>
  <a href="<?= Tt::getPath(2) ?>/sendPage" class="privMsgs"><i></i>Написать</a>
  <div class="clear"><!-- --></div>
</div>


