<p>
  <label for="noborders"><input type="checkbox" id="noborders" /> без разделителей</label>
</p>
<p>
  <label for="fullwidth"><input type="checkbox" id="fullwidth" /> растянуть на всю ширину</label>
</p>

<script type="text/javascript">

var ed = tinyMCEPopup.editor;

function hasClass(cls, className){
  return cls.clean().contains(className, ' ');
};
function removeClass(cls, className) {
  return cls.replace(new RegExp('(^|\\s)' + className + '(?:\\s|$)'), '$1')
};
function addClass(cls, className) {
  if (!hasClass(cls, className)) className = (cls + ' ' + className).clean();
  return className;
};

var eTable = ed.dom.getParent(ed.selection.getNode(), 'table');
var cls = ed.dom.getAttrib(eTable, 'class');
$('noborders').set('checked', hasClass(cls, 'noborders'));
$('fullwidth').set('checked', hasClass(cls, 'fullwidth'));

function okAction() {
  cls = $('noborders').get('checked') ? addClass(cls, 'noborders') : removeClass(cls, 'noborders');
  cls = $('fullwidth').get('checked') ? addClass(cls, 'fullwidth') : removeClass(cls, 'fullwidth');
  ed.dom.setAttrib(eTable, 'class', cls);
  return true;
};

</script>
