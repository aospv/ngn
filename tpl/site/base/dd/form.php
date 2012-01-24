<style>
<? if (isset($_REQUEST['default'][DdCore::masterFieldName])) { ?>
/* Скрываем меню с выбором мастер-записи */
.type_ddItemsSelect {
display: none;
}
<? } ?>
</style>

<div class="apeform">
  <?= $d['form'] ?>
</div>
