<? Tt::tpl('admin/modules/pages/header', $d) ?>
<div class="apeform">
  <?= $d['form'] ?>
</div>

<script type="text/javascript">

// Ресайз картинок
// ---------------------------------
var eForm = $('PageControllerSettingsForm');
Ngn.cp.addFormValidation(eForm, (function() { return Ngn.ri.formValidation() }));
Ngn.ri = new Ngn.cp.ResizeImages((function() { eForm.submit() }));

// Кнопочка редактирования структуры
// ---------------------------------

</script>
