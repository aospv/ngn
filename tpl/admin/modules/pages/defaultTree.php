<link rel="stylesheet" type="text/css" href="./i/css/admin/pagesTree.css" media="screen, projection" />
<div class="pagesTree">
  <div class="col1" id="col1" style="width:200px; float: left;">
    <? Tt::tpl('admin/modules/pages/treeEdit', $d) ?>
    <div style="width:200px"><!-- --></div>
  </div>
  <a id="dragBtn" style="float: left;"></a>
</div>

<?php /* 
<table cellspacing="0" class="pagesTree valign" style="float: left;">
<tr>
  <td>
  <div style="width: 50px;height:50px;border:1px solid #FF0000" id="asd">123</div>
  </td>
  <td class="col2" width="100%">
    <? Tt::tpl('admin/modules/pages/default', $d) ?>
  </td>
</tr>
</table>
*/?>
 
<script>
var dragBtn = $('dragBtn');
new Drag($('col1'), {
  handle: $('dragBtn'),
  modifiers: {x: 'width', y: ''},
  onStart: function(el) {
    //c($('mainContent').getSize().y);
    
    dragBtn.addClass('dragging');
  },
  onComplete: function(el) {
    dragBtn.removeClass('dragging');
  },
});

//$('.bottom').setStyle('margin-top', '0');

</script>