<? Tt::tpl('admin/modules/tags/header', $d) ?>

<div class="tags">
  <div id="treeMenu" class="iconsSet">
    <small>
      <a href="#" class="add gray"><i></i><?= LANG_CREATE ?></a>
      <a href="#" class="rename gray"><i></i><?= LANG_RENAME ?></a>
      <a href="#" class="delete gray"><i></i><?= LANG_DELETE ?></a>
      <a href="#" class="toggle collapse gray"><i></i>Развернуть все</a>
    </small>
    <div class="clear"><!-- --></div>
  </div>
  <div id="treeContainer"></div></div>
</div>

<?= SFLM::getJsTags('mif.tree') ?>

<script type="text/javascript">
var te;
var setHeight = function() {
  var h = Ngn.cp.getMainAreaHeight()
    - $('mainContent').getElement('.navSub').getSize().y
    - $('treeMenu').getSize().y;
  te.container.setStyle('height', h+'px'); 
};

$('body').addClass('twopanels');
window.addEvent('domready', function() {
  te = new Ngn.TreeEditTags(
    'treeContainer',
    '<?= $d['groupId'] ?>',
    { buttons: 'treeMenu' }
  ).init();
  setHeight();
  window.addEvent('resize', setHeight);
});



</script>