Ngn.NotifyTypeEdit = new Class({
  initialize: function(btn, el) {
    this.id = el.get('id');
  
    this.fx = new Fx.Slide(el, {
      duration: 500,
      transition: Fx.Transitions.Pow.easeOut
    });
    
    this.getState() ? this.fx.show() : this.fx.hide();
    
    btn.addEvent('click', function(e){
      this.fx.toggle();
      return false;
    }.bind(this));
    
    this.fx.addEvent('complete', function(){
      this.setState(this.fx.open);
    }.bind(this));
    
    el.getElements('a.sm-delete').each(function(eDelBtn){
      eDelBtn.addEvent('click', function() {
        var ul = eDelBtn.getParent().getParent().getParent();
        new Request({
          url: eDelBtn.get('href').replace('?a=', '?a=ajax_'),
          onComplete: function() {
            eDelBtn.getParent().getParent().dispose();
            this.fx.show();
            if (!ul.getChildren().length) {
              ul.dispose();
              this.fx.hide();
            }
          }.bind(this)
        }).send();
        return false;
      }.bind(this));
    }.bind(this));
  },
  getState: function() {
    return Cookie.read(this.id + 'state') == 'true' ? true : false;
  },
  setState: function(state) {
    Cookie.write(this.id + 'state', state);
  }
});

Ngn.cutItemTitles = function(eItems) {
  var first = eItems.getElement('div[class~=item]');
  var maxWidth = first.getSize().x;
  var editBlock = first.getElement('div[class~=editBlock]');
  if (editBlock) maxWidth = maxWidth - editBlock.getSize().x;
  abbreviate($$('.ddItems .item h2 a'), Math.round(maxWidth / 10));
}

