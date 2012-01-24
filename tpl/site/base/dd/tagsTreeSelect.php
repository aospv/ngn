<div class="tagsTreeSelect" id="tagsTreeSelect_<?= $d['name'] ?>">
<?= Tt::getDbTree(
  $d['tree'], 
  '`<li>↓ <a href="#" data-id="`.$id.`" class="pseudoLink">`.$title.`</a></li>`', 
  '`<li><input type="radio" name="'.$d['name'].
    '" value="`.$id.`" id="f_`.$id.`"`.($id == $value ? ` checked` : ``).` /><label for="f_`.$id.`">`.$title.`</label></li>`', 
  '`<ul class="nodes_`.$id.`">`', 
  '`</ul>`', 
  array(
    'value' => $d['value']
  )
) ?>
</div>
