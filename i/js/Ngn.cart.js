Ngn.cart = {};

Ngn.cart.OrderList = new Class({
  
  initialize: function(eProducts, btnClean, options) {
    this.setOptions(options);
    btnClean.addEvent('click', function(e) {
      e.preventDefault();
      if (!Ngn.confirm()) return;
      if (Ngn.cart.block) Ngn.cart.block.clear();
    });
    
    eProducts.getElements('.delete').each(function(eBtn) {
      eBtn.addEvent('click', function(e){
        e.preventDefault();
        if (!Ngn.confirm()) return;
        if (Ngn.cart.block) Ngn.cart.block.removeItem(eBtn.get('data-pageId'), eBtn.get('data-itemId'));
        new Request({
          url: '/c/storeCart/ajax_delete',
          onComplete: function() {
            window.location.reload(true);
          }
        }).get({
          pageId: eBtn.get('data-pageId'),
          itemId: eBtn.get('data-itemId')
        });
      });
    });
  }

});

Ngn.cart.Block = new Class({
  Implements: [Options],
  
  options: {
    storeOrderController: 'storeOrder',
  },

  eProduct: null,
  
  initialize: function(eCart, options) {
    this.setOptions(options);
    this.eCart = eCart;
    this.eCart.set('html', '');
    this.eCaption = new Element('a', {
      href: '/' + this.options.storeOrderController,
      styles: {
        'display': 'none'
      }
    }).inject(this.eCart);
    this.eLabel = new Element('span', {
      html: 'Корзина: '
    }).inject(this.eCaption);
    this.eCount = new Element('span').inject(this.eLabel, 'after');
    this.initCart();
  },
  
  count: 0,
  
  initCart:  function() {
    for (var i in this.cartData) this.count += this.cartData[i];
    if (this.count) this.refrashCart();
  },
  
  removeItem: function(pageId, itemId) {
    delete this.cartData[pageId+'_'+itemId];
    this.storeData();
    this.refrashCart();
  },
  
  clear: function() {
    this.cartData = {};
    this.storeData();
    this.refrashCart();
  },
  
  storeData: function() {
    Ngn.storage.json.set('cart', this.cartData);
  },
  
  addItem: function(pageId, itemId) {
    this.count++;
    this.cartData[pageId+'_'+itemId] = this.count;
    this.storeData();
    this.refrashCart();
    this.addToCart(pageId, itemId);
    this.showMove();
  },
  
  refrashCart: function() {
    this.eCaption.setStyle('display', this.count ? 'block' : 'none');
    this.eCount.set('html', this.count);
  },
  
  addToCart: function(pageId, itemId) {
    new Request({
      url: '/c/storeCart/ajax_add'
    }).post({
      pageId: pageId,
      itemId: itemId
    });
  },
  
  showMove: function() {
    if (!this.eProduct) return;
    var el = this.eProduct;
    var eClone = el.clone().inject(document.getElement('body'), 'bottom').setStyle('position', 'absolute');
    var pos = el.getPosition();
    eClone.setPosition(pos);
    var size = eClone.getSize();
    var pos2 = this.eCart.getPosition();
    new Fx.Morph(eClone).start({
      'top': [pos.y, pos2.y],
      'left': [pos.x, pos2.x],
      'width': [size.x+'px', 50+'px'],
      'height': [size.y+'px', 15+'px'],
      'opacity': [1, 0]
    });
  }
  
});

Ngn.cart.initBlock = function(el, options) {
  c(options);
  Ngn.cart.block = new Ngn.cart.Block(el, options);
}; 
