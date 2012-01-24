<link rel="stylesheet" type="text/css" href="/i/js/tiny_mce/plugins/table/css/table.css" media="screen, projection" />

<table class="valignSimple selectCR">
<tr>
<td>
<?
$cols = 10; $row = 10;
print '<table id="selectCR" cellpadding="0" cellspacing="0">';
for ($i=0; $i<$cols; $i++) {
  print "<tr>";
  for ($j=0; $j<$row; $j++) {
    print '<td><a href=""><span></span></a></td>';
  }
  print "</tr>";
}
print "</table>";
?>
</td><td class="settingsCell">
  <p>
    Название таблицы:<br />
    <textarea id="tableTitle"></textarea>
  </p>
  <p>
    <label for="noborders"><input type="checkbox" id="noborders" /> без разделителей</label>
  </p>
  <p>
    <label for="fullwidth"><input type="checkbox" id="fullwidth" /> растянуть на всю ширину</label>
  </p>
  <p id="tableSize">
    Размер таблицы: 
    <span id="cols"></span> x
    <span id="rows"></span>
  </p>
</td></tr>
</table>

<script src="/i/js/ngn/Ngn.selectCR.js" type="text/javascript"></script>
<script type="text/javascript">

function tableHtml() {
  var cols = parseInt($('cols').get('text'));
  var rows = parseInt($('rows').get('text'));
  if (!cols) {
    alert('Вы не определили размер таблицы');
    return false;
  }
  var html = '';
  var asd = [];
  if ($('noborders').get('checked')) asd.push('noborders');
  if ($('fullwidth').get('checked')) asd.push('fullwidth');
  html += '<table data-mce-new="1" cellspacing="0" class="'+
  (asd.length ? asd.join(' ') : '') + '">';
  var tableTitle = $('tableTitle').get('value');
  if (tableTitle) {
    html += '<caption><br data-mce-bogus="1"/>'+tableTitle+'</caption>';
  }
  html += '<tbody>';
  for (var i=0; i<rows; i++) {
    html += '<tr>';
    for (var j=0; j<cols; j++) {
      html += '<td><br data-mce-bogus="1"/></td>';
    }
    html += '</tr>';
  }
  html += '</tbody>'+
          '</table>';
  return html;
}

var ed = tinyMCEPopup.editor;

function okAction() {
  var html = tableHtml();
  if (!html) return false;
  var patt = '';

  ed.focus();
  ed.selection.setContent('<br class="_mce_marker" />');
  tinymce.each('h1,h2,h3,h4,h5,h6,p'.split(','), function(n) {
    if (patt)
      patt += ',';
    patt += n + ' ._mce_marker';
  });
  tinymce.each(ed.dom.select(patt), function(n) {
    ed.dom.split(ed.dom.getParent(n, 'h1,h2,h3,h4,h5,h6,p'), n);
    ed.dom.setOuterHTML(ed.dom.select('br._mce_marker')[0], html);
  });
  tinymce.each(ed.dom.select('table[data-mce-new]'), function(node) {
    var td = ed.dom.select('td', node);
    try {
      // IE9 might fail to do this selection
      ed.selection.select(td[0], true);
      ed.selection.collapse();
    } catch (ex) {
      // Ignore
    }
    ed.dom.setAttrib(node, 'data-mce-new', '');
  });
  ed.execCommand('mceEndUndoLevel');
  return true;
}

$('tableSize').setStyle('display', 'none');
Ngn.selectCR($('selectCR'), function(r) {
  $('tableSize').setStyle('display', 'block');
  $('rows').set('html', r[0]);
  $('cols').set('html', r[1]);
});

</script>
