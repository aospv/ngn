<? Tt::tpl('admin/modules/pages/header', $d) ?>
<form action="<?= Tt::getPath() ?>" method="post">
  <input type="hidden" name="action" value="move" />
  <input type="hidden" name="id" value="<?= $d['ep']['id'] ?>" />
  <p><b><?= LANG_FIND_AND_CHOOSE_SECTION ?></b></p>
  <p><?= LANG_SEARCH_SHOWS_RESULTS ?></p>
  <div class="cols">
    <div class="col" style="width:140px">
      <? Tt::tpl('common/autocompleter', array('name' => 'folder')) ?>
    </div>
    <div class="col" style="width:100px">    
      <input type="submit" value="<?= LANG_MOVE ?>" style="width:100px;height:23px;" />
    </div>
  </div>
</form>
<div class="clear"><!-- --></div>
<form action="<?= Tt::getPath() ?>" method="post">
  <input type="hidden" name="action" value="move" />
  <input type="hidden" name="id" value="<?= $d['ep']['id'] ?>" />
  <input type="hidden" name="page" value="0" />
  <p><b>Или</b></p>
  <input type="submit" value="Переместить в корень" style="width:150px;height:23px;" />
</form>