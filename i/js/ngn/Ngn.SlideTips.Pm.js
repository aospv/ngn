Ngn.SlideTips.Pm = new Class({
  
  Extends: Ngn.SlideTips,
  
  init: function() {
    this.fxs.each(function(fx, n){
      var eMsg = fx.eMsg = fx.element.getElement('.pmw');
      fx.addEvent('onComplete', function() {
        var eMsgsBtn = this.eWin.getPrevious('a');
        var system = fx.eMsg.hasClass('pmSystem');
        if (system) {
          this.eWin.addClass('slideSystem');
        } else {
          this.eWin.removeClass('slideSystem');
        }
        this.fireEvent('onSystem', system);
      }.bind(this));
      
      var i = new Element('i');
      var a = new Element('a', {
        'href': '123',
        'class': 'smIcons sm-publish',
        'title': 'Пометить как прочитанное',
        'events': {
          'click': function(){
            //this.markViewed(eMsg.get('id').replace('pmw', ''));
            return false;
          }.bind(this)
        }
      });
      i.inject(a);
      a.inject(eMsg, 'top');
      
      // На все ссылки в слайде добавляем удаление текущего слайда
      fx.element.getElements('a').each(function(eLink){
        eLink.addEvent('click', function() {
          this.markViewed(eMsg.get('id').replace('pmw', ''));
        }.bind(this));
      }.bind(this)); 
      
    }.bind(this));
  },
  
  markViewed: function(id) {
    new Request({
      url: this.options.url + '?a=ajax_view&id=' + id,
      onComplete: function(html) {
        this.removeCurrentSlide();
      }.bind(this)
    }).send();
  }

});