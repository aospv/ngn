<div id="treeMenu" class="tools">
  <a href="#" class="iconBtn openFolder" title="Открыть папку"><i></i></a>
  <a href="#" class="iconBtn add" title="Создать раздел"><i></i></a>
  <? if ($d['god']) { ?>
  <a href="#" class="iconBtn add2" title="Создать раздел (ручное)"><i></i></a>
  <? } ?>
  <a href="#" class="iconBtn edit" title="Редактировать содержание"><i></i></a>
  <a href="#" class="iconBtn link" title="Открыть раздел на сайте"><i></i></a>
  <!-- <a href="#" class="iconBtn rename" title="<?= LANG_RENAME ?>"><i></i></a> -->
  <a href="#" class="iconBtn editProp" title="Редактировать параметры"><i></i></a>
  <? if (AdminModule::isAllowed('pageMeta')) { ?>
  <a href="#" class="iconBtn meta" title="Редактировать мета-теги"><i></i></a>
  <? } ?>
  <? if ($d['god']) { ?>
  <a href="#" class="iconBtn editOptions" title="Редактировать свойства контроллера"><i></i></a>
  <a href="#" class="iconBtn pageBlocks" title="Редактировать блоки"><i></i></a>
  <a href="#" class="iconBtn layout" title="Редактировать формат страницы"><i></i></a>
  <a href="#" class="iconBtn list ddOutput" title="Управление выводом полей"><i></i></a>
  <a href="#" class="iconBtn privileges" title="Привилегии"><i></i></a>
  <? } ?>
  <a href="#" class="iconBtn delete" title="Удалить раздел"><i></i></a>
  <!-- <a href="#" class="iconBtn toggle collapse" title="Развернуть все"><i></i></a> -->
  <div class="clear"><!-- --></div>
</div>

<div id="treeContainerWrapper"><div id="treeContainer"></div></div>

<script type="text/javascript" src="/i/js/ngn/Ngn.cp.PagesInterface.js"></script>
<script type="text/javascript">
$('body').addClass('newLayout');
window.addEvent('domready', function() {
  new Ngn.cp.PagesInterface();
});
</script>
