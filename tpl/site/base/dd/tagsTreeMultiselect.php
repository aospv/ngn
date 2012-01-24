<?php // prr($d['tree']); return; ?>
<div class="tagsTreeSelect" id="tagsTreeMultiselect_<?= $d['name'] ?>">
<?= Tt::getDbTree(
  $d['tree'], 
  '`<li>↓ <a href="#" id="node_`.$id.`">`.$title.`</a></li>`', 
  '`<li id="leaf_`.$id.`_`.$parentId.`"><input type="checkbox" name="' . $d['name'] .
  '[]" value="`.$id.`" id="f_`.$id.`"`.(in_array($id, $values) ? ` checked` : ``).` /><label for="f_`.$id.`"> `.$title.`</label></li>`', 
  '`<ul id="nodes_`.$id.`">`', 
  '`</ul>`', 
  array(
    'values' => is_array($d['value']) ? $d['value'] : array()
  )
) ?>
</div>
<script type="text/javascript">
window.addEvent('domready', function(){
  new Ngn.TagsTreeSelect('tagsTreeMultiselect_<?= $d['name'] ?>');
});
</script>