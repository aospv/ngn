window.addEvent('domready',function(){
      (function($) {
        //gets the selected text
        var getSelection = function() {
          return $try(
            function() { return window.getSelection(); },
            function() { return document.getSelection(); },
            function() { 
                  var selection = document.selection && document.selection.createRange();
              if(selection.text) { return selection.text; }
              return false;
                }
          ) || false;
        };
        //vars 
        var url = 'http://davidwalsh.name/?s={term}', selectionImage;
        //event to listen
        $('content-area').addEvent('mouseup',function(e) {
          var selection = getSelection();
          if(selection && (selection = new String(selection).replace(/^\s+|\s+$/g,''))) {
            //ajax here { http://davidwalsh.name/text-selection-ajax }
            if(!selectionImage) {
              selectionImage = new Element('a',{
                href: url,
                opacity:0,
                id: 'selection-image',
                title: 'Click here to learn more about this term',
                target: '_blank'
              }).inject(document.body,'top');
            }
            //handle the every-time event
            //alert(selection);
            selectionImage.set('href',url.replace('{term}',encodeURI(selection))).setStyles({
              top: e.page.y - 30, //offsets
              left: e.page.x - 13 //offsets
            }).tween('opacity',0);
          }
        });
        
        $(document.body).addEvent('mousedown',function() {
          //hider
          if(selectionImage) { selectionImage.tween('opacity',0); }
        });
        
      })(document.id);
    });