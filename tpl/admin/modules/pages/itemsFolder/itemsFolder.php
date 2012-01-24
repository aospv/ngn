<? Tt::tpl('dd/itemsFolder', $d) ?>

<script type="text/javascript">
var oST = new Sortables('#structures', {
  revert: true,
  clone: true
});
oST.addEvent('start', function(el, clone){
  clone.addClass('move');
});
oST.addEvent('stop', function(el, clone){
  clone.removeClass('move');
});
oST.addEvent('complete', function(el, clone){
  new Request({
    url: window.location.pathname + '?a=ajax_reorder',
    onComplete: function() {
    }.bind(this)
  }).POST({
    'structures' : oST.serialize(2, function(element, index){
      return element.getProperty('id').replace('str_','');
    })
  });    
});
</script>