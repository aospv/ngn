<? Tt::tpl('admin/modules/stm/header', $d) ?>
<?
Tt::tpl('common/form', array(
  'form' => $d['form'],
  'forceDefaultInit' => true
))
?>

<script type="text/javascript" src="/i/js/ngn/Ngn.frm.stmEditFieldsSaver.js"></script>
<script type="text/javascript">
var form = new Ngn.Form(document.getElement('.apeform form'), {
  equalElementHeights: true
});
Ngn.frm.stmEditFieldsSaver.delay(500, null, {
  formId: form.eForm.get('id'), 
  updateAction: 'ajax_updateMenu',
  fancyUploadAction: 'json_menuFancyUpload',
  sessionId: '<?= session_id() ?>',
  useSaver: true
});
</script>