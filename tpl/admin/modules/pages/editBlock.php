<? Tt::tpl('admin/modules/pages/header', $d) ?>
<h2>Тип блока: <b><?= $d['typeTitle'] ?></b></h2>
<div class="apeform"><?= $d['form'] ?></div>
<script type="text/javascript">
//$$('.type_textarea')

new Ngn.TinyInitDdAbstract();

//tinyMCE.init(new Ngn.TinySettings().getSettings({
//  elements: 'texti',
//  attachId: 'pageBlock_<?= $d['page']['id'].'_'.$d['id'] ?>'
//}));

</script>
