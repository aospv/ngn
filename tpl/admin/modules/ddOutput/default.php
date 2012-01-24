<? Tt::tpl('admin/modules/ddOutput/header', $d) ?>

<script type="text/javascript">
window.addEvent('domready', function(){
  new Ngn.ItemsTable();
  new Ngn.frm.Saver($('form'), {
    url: Ngn.getPath(4) + '?a=json_updateFieldsOutputSettings'
  });
});
</script>

<style>
.selected {
background-color: #FEFFBF;
}
#itemsTable select {
width: 60px;
}
.loader {
background-position: 27px 5px;
background-image: none;
}
.loading .loader {
background-image: url(./i/img/black/loader.gif) !important;
}
</style>

<?

print '<form action="" method="post" id="form">';
print '<input type="hidden" name="action" value="updateFieldsOutputSettings" />';
print '<table cellpadding="0" cellspacing="0" id="itemsTable" class="valign" id="itemsTable">';
// -------head----------
print '<thead>';
print '<tr>';
print '<td>&nbsp;</td>';
print '<td>Название поля</td>';
print '<td>Тип поля</td>';
foreach ($d['layouts'] as $name => $layout) {
  print '<td'.($name == $d['curLayoutName'] ? ' class="selected"' : '').'>';
  print '<a href="'.Tt::getPath(3).'/'.$name.'" class="tooltip" title="Включить сортировку для шаблона «'.$name.'»">'.$layout['title'].'</a>';
  print '</td>';
}
print '</tr>';
print '</thead>';

/* @var $oS DdoSettings */
$oS = $d['oS'];

// -------head----------
$proc = round(100 / count($d['layouts'])+1);
print '<tbody>';
foreach ($d['fields'] as $field) {
  print '<tr id="item_'.$field['name'].'">';
  print '<td class="loader"><div class="dragBox"></div></td>';
  print '<td nowrap>&nbsp;'.
        '<small>'.$field['title'].'</small></td>';
  print '<td><img src="'.DdFieldCore::getIconPath($field['type']).'" title="'.$field['type'].'" class="tooltip"></td>';
  foreach ($d['layouts'] as $layoutName => $layout) {
    $checked = isset($d['settings']['show'][$layoutName][$field['name']]);
    print '<td width="'.$proc.'%" nowrap>';
    print '<input class="tooltip" title="'.($checked ? 'Включено' : 'Выключено').
      '" type="checkbox" name="show['.$layoutName.']['.$field['name'].']" value="1"'.
    ($checked ? ' checked' : '').' />&nbsp;';
    $oS->getOutputMethods($field['type']);
    print '<select class="tooltip" title="Способ отображения" name="outputMethod['.$layoutName.']['.$field['name'].']">'.
      Html::select(
        null,
        Arr::get($oS->getOutputMethods($field['type']), 'title', 'name'),
        $d['settings']['outputMethod'][$layoutName][$field['name']],
        array('noSelectTag' => true)
      );
    print '</select></td>';
  }
  print '</tr>';
}
print '</tbody>';
print '</table>';
// print '<p><input type="submit" value="Сохранить" style="width:150px;height:20px;" /></p>';
print '</form>';
