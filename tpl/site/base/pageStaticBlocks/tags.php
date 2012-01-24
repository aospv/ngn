<?
  if (!empty($d['settings']['tagField'])) {
    $k = $d['settings']['tagField'];
    $oF = new DdFields($d['page']['strName'], $d['page']['id']);
    $tagField = $oF->getDataByName($k);
    $tags = $d['tags'][$k];
  }        
  if (isset($tags)) { ?>
<div class="box">
  <?= '<h2>'.$tagField['title'].'</h2>' ?>
  <div class="boxBody" id="boxBody">
    <?= 
    DdTagsHtml::treeUl(
      $tags,
      '`<a href="'.$d['page']['path'].'/t.`.'.
      (strstr($tagField['type'], 'Tree') ? '$id' : '$name').
      '.`"`.($selected ? ` class="selected"` : ``).`><i></i><span>`.$title.'.
      ($d['settings']['showTagsCounts'] ? '` (`.$cnt.`)' : '`').
      '</span></a>`',
      !empty($d['tagsSelected']) ? Arr::get($d['tagsSelected'], 'id') : array(),
      !empty($d['settings']['showNullCountTags'])
    )
    ?>
  </div>
  <script type="text/javascript">
  <?/*
  window.addEvent('domready', function(){
    $('boxBody').getElement('li').getChildren('a').each(function(el){
      var eNext = el.getNext();
      if (!eNext) return;
      var fx1 = new Fx.Slide(eNext, {
        duration: 500,
        transition: Fx.Transitions.Pow.easeOut
      }).hide();
      el.addEvent('click', function(e) {
        fx1.toggle();
        return false;
      });
    });
  });
  */?>
  $('boxBody').getElements('a').each(function(el){
    if (el.hasClass('selected')) {
      var elPar = el.getParent();
      while (1) {
        if (elPar.get('id') == 'boxBody') break;
        if (elPar.get('tag') == 'ul')
          elPar.setStyle('display', 'block');
        elPar = elPar.getParent();
      }
    }
    el.addEvent('click', function(e) {
      var eNext = el.getNext();
      if (!eNext) return;
      el.getNext().setStyle('display',
        (el.getNext().getStyle('display') == 'none' ? 'block' : 'none'));
      return false;
    });
  });
  </script>
  <style>
  .box ul ul {
  display: none;
  }
  </style>          
</div>
<? } ?>