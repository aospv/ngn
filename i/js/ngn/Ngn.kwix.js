Ngn.kwix = {

  start: function(){
    Ngn.kwix.parseKwicks();
  },

  parseKwicks: function(){
    var squeezeTo;
    var maxWidth = 300;
    var startWidths = [];
    var kwicks = $$('#kwick .kwick');
    var fx = new Fx.Elements(kwicks, {
      wait: false,
      duration: 250,
      transition: Fx.Transitions.Cubic.easeOut,
      link: 'cancel',
      onStart: function(eKwick) {
        eKwick.store('fxProgress', true);
      },
      onComplete: function(eKwick) {
        eKwick.store('opened', parseInt(eKwick.getStyle('width')) == maxWidth);
        eKwick.store('fxProgress', false);
      }
    });
    var kwickWidth = Math.round($('kwick').getSize().x / kwicks.length)-1;
    kwicks.each(function(eKwick, i) {
      var eText, fxText, eTitle, close;
      eKwick.store('opened', false);
      eKwick.store('fxProgress', false);
      eTitle = eKwick.getElement('h2');
      eTitle.set('html', eTitle.get('html').trim().replace(/\n/g, '<br />'));
      eText = eKwick.getElement('.text');
      if (eText) {
        eText.set('html', eText.get('html').trim().replace(/\n/g, '<br />'));
        fxText = new Fx.Tween(eText, {
          property: 'opacity',
          duration: 250,
          link: 'cancel'
        }).set('opacity', 0);
      }
      //startWidths[i] = eKwick.getStyle('width').toInt();
      eKwick.setStyle('width', kwickWidth+'px');
      startWidths[i] = kwickWidth;
      eKwick.addEvent('mouseenter', function(e){
        if (fxText && !eKwick.retrieve('opened') && eText.get('opacity') == 0)
          fxText.start(0, 1);
        var obj = {};
        obj[i] = {width: [eKwick.getStyle('width').toInt(), maxWidth]};
        var counter = 0;
        kwicks.each(function(other, j) {
          if (other != eKwick){
            var w = other.getStyle('width').toInt();
            if (w != squeezeTo) obj[j] = {'width': [w, squeezeTo] };
          }
        });
        fx.start(obj);
      });
      eKwick.addEvent('mouseleave', function() {
        //if (!eKwick.retrieve('opened')) return;
        if (fxText && eText.get('opacity') != 0) fxText.start(1, 0);
      })
    });
    squeezeTo = (kwicks.length * startWidths[0] - maxWidth) / (kwicks.length - 1);
    
    $('kwick').addEvent('mouseleave', function(e){
      var obj = {};
      kwicks.each(function(other, j){
        obj[j] = {width: [other.getStyle('width').toInt(), startWidths[j]]};
      });
      fx.start(obj);
    });
  }
};
