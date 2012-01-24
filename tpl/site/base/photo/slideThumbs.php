<div id="thumbsBlock">
  <div class="inner">
    <? foreach ($d['items'] as $v) {?>
      <a href="<?= $v['link'] ?>" class="thumb<?= $v['id'] == $d['selected'] ? ' selected' : '' ?>"><img src="<?= $v['sm_image'] ?>" /></a>
    <? } ?>
    <div class="clear"></div>
  </div>
</div>

<script>

//Carousel

window.addEvent('domready',function(){
  
  var hl = new Fx.Scroll.Carousel('thumbsBlock', {
    childSelector: 'a',
    mode: 'vertical',
    reverse: true
  });
  
  //new Scroller($('thumbsBlock'), {onChange: function(){
    //c('+');
  //}});
  
  //var moveCarousel = function() {
  //    hl.toNext();
  //};
  //moveCarousel.periodical(3000);
});

</script>