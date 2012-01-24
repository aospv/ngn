<?php 
        if (!isset($author) and $d['action'] == 'list' and !empty($d['tags'])) {
          if (!empty($d['settings']['tagField'])) {
            $k = $d['settings']['tagField'];
            $oF = new DdFields($d['page']['strName'], $d['page']['id']);
            $tagField = $oF->getDataByName($k);
            $tags = $d['tags'][$k];
          }
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
              '.`"`.($selected ? ` class="selected"` : ``).`><i></i><span>`.$title.` (`.$cnt.`)</span></a>`',
              !empty($d['tagsSelected']) ? Arr::get($d['tagsSelected'], 'id') : array(),
              !empty($d['settings']['showNullCountTags'])
            )
            ?>
          </div>
          <script type="text/javascript">
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