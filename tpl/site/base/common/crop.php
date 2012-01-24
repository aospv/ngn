<?php 

?>
<script type="text/javascript">
var DivID       =   $('CropFrom');
var ImgID       =   $('CropMe');
//var InputUrlID  =   $('UrlID');
//var InputTopID  =   $('TopID');
//var InputLeftID =   $('LeftID');
 
//InputUrlID.set('value',ImgID.get('src'));   // Add URL to form
var DragInDiv = new Drag(DivID, {
  modifiers: {
    x: 'scrollLeft',
    y: 'scrollTop',
  },
  style: false,
  invert: true,
  onSnap: function(el){
    ImgID.setStyles({'opacity':0.5})
  },
  onComplete: function(el){
  ImgID.setStyles({'opacity':1})
  //InputTopID.set('value', ImgID.getCoordinates(DivID).top); // Add new TOP to form
  //InputLeftID.set('value', ImgID.getCoordinates(DivID).left); // Add new LEFT to form
}
});
</script>

<style>
#CropFrom {
  height      :   100px;
  width       :   100px;
  overflow    :   hidden;
  cursor      :   move;
  margin      :   0 auto;
}
</style>

<div id="CropFrom">
  <img id="CropMe" src="<?= Misc::getFilePrefexedPath($d['image'], 'md_') ?>"  />
</div>
<input id="UrlID" type="hidden" name="url" value="" />
<input id="TopID" type="hidden" name="top" value="0" />
<input id="LeftID" type="hidden" name="left" value="0" />
