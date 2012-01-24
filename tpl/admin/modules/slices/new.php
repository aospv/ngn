<? Tt::tpl('admin/modules/slices/header', $d) ?>
<div class="apeform">
  <?= $d['form'] ?>
</div>
<script type="text/javascript">
tinyMCE.init(new Ngn.TinySettings().getSettings({
  'elements': 'texti'
}));          
</script>