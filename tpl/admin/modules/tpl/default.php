<? Tt::tpl('admin/modules/tpl/header') ?>
<style>
.col {
width: 250px;
}
</style>
<div class="col">
  <h2>NGN-шаблоны</h2>
  <? Tt::tpl('admin/modules/tpl/items', $d['lists']['ngn']) ?>
</div>
<div class="col">
  <h2>Мастер-шаблоны</h2>
</div>
<div class="col">
  <h2>Шаблоны проекта <?= SITE_TITLE ?></h2>
  <? Tt::tpl('admin/modules/tpl/items', $d['lists']['site']) ?>
</div>
<div class="col">
  <h2>Шаблоны темы «<?= TPL_THEME ?>»</h2>
  <? Tt::tpl('admin/modules/tpl/items', $d['lists']['theme']) ?>
</div>
<div class="clear"><!-- --></div>