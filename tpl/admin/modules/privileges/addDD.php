<? Tt::tpl('admin/modules/privileges/header') ?>

<h2>Назначение привилегий</h2>
<p>
  Назначте привилегию на это поле и пользователь сможет видеть его.
</p>

<form action="<?= Tt::getPath() ?>" method="POST">
  <input type="hidden" name="action" value="createDD" />
  <input type="submit" value="Назначить" style="width:150px; height:30px;" />
  <div class="col" style="width:170px;">
    <p><b>Найдите и выберите пользователя:</b></p>
    <? Tt::tpl('common/search', array('name' => 'user')) ?>
  </div>
  <div class="col" style="width:170px;">
    <p><b>Найдите и выберите поле:</b></p>
    <? Tt::tpl('common/search', array('name' => 'page')) ?>
  </div>
  <div class="col" style="width:170px;">
    <? Tt::tpl('common/checkboxes', array('name' => 'types', 'items' => $d['types'])) ?>
  </div>
</form>