Ngn.cart = {};

Ngn.NumberSelect = new Class({
  Implements: [Options, Events],
  
  options: {
    min: 1,
    max: 1000,
    step: 1,
    //onChange: null
  },

  initialize: function(eInput, options) {
    this.setOptions(options);
    this.eInput = eInput;
    eInput.addEvent('blur', function() {
      eInput.set('value', parseInt(this.eInput.get('value')));
      this.fireEvent('change', parseInt(this.eInput.get('value')));
    }.bind(this));
    eInput.addEvent('keypress', function() {
      this.fireEvent('change', parseInt(this.eInput.get('value')));
    }.bind(this));
    new Element('input', { 'class' : 'btnInput prev', type: 'button' }).inject(eInput, 'before').
      addEvent('click', this.prev.bind(this));
    new Element('input', { 'class' : 'btnInput next', type: 'button' }).inject(eInput, 'after').
      addEvent('click', this.next.bind(this));
  },

  prev: function() {
    var curValue = parseInt(this.eInput.get('value')) - this.options.step < this.options.min ?
      this.options.min :
      parseInt(this.eInput.get('value')) - this.options.step;
    this.eInput.set('value', curValue);
    this.fireEvent('change', parseInt(this.eInput.get('value')));
  },

  next: function() {
    var curValue = parseInt(this.eInput.get('value')) + this.options.step > this.options.max ?
    this.options.max :
      parseInt(this.eInput.get('value')) + this.options.step;
    this.eInput.set('value', curValue);
    this.fireEvent('change', parseInt(this.eInput.get('value')));
  }
  
});

Ngn.cart.OrderList = new Class({
  
  initialize: function(eProducts, btnClean) {
    this.eTotalV = eProducts.getElements('tr.total .priceV');
    this.eProducts = eProducts;
    btnClean.addEvent('click', function(e) {
      e.preventDefault();
      if (!Ngn.confirm()) return;
      if (Ngn.cart.block) Ngn.cart.block.clear(true);
    });
    this.eProducts.getElement('tbody').getElements('tr').each(function(eTr) {
      var pageId = eTr.get('data-pageId');
      var itemId = eTr.get('data-itemId');
      var eBtn = eTr.getElement('.delete');
      eBtn.addEvent('click', function(e){
        e.preventDefault();
        if (!Ngn.confirm()) return;
        if (Ngn.cart.block) Ngn.cart.block.removeItem(pageId, itemId, true);
      });
      var eCnt = eTr.getElement('.cntV');
      var cnt = parseInt(eCnt.get('html'));
      new Ngn.NumberSelect(
        new Element('input', {
          value: cnt,
          'class': 'fld',
          maxlength: 3
        }).replaces(eCnt), {
          onChange: function(n) {
            this.refrashTotal();
            if (Ngn.cart.block) Ngn.cart.block.updateCnt(pageId, itemId, n);
          }.bind(this)
        }
      );
    }.bind(this));
  },
  
  refrashTotal: function() {
    var total = 0;
    this.eProducts.getElement('tbody').getElements('tr').each(function(eTr) {
      total += eTr.getElement('.fld').get('value') * eTr.getElement('.priceV').get('html');
    });
    this.eTotalV.set('html', total);
  }

});

Ngn.cart.utils = {
  
  initData: function(callback) {
    new Ngn.Request.JSON({
      url: '/c/storeCart/json_getIds',
      onComplete: function(d) {
        callback(d);
      }
    }).send();
  },
  
  remove: function(pageId, itemId, callback) {
    new Ngn.Request({
      url: '/c/storeCart/ajax_delete',
      onComplete: function() {
        callback();
      }
    }).get({
      pageId: pageId,
      itemId: itemId
    });
  },
  
  add: function(pageId, itemId) {
    new Request({
      url: '/c/storeCart/ajax_add'
    }).get({
      pageId: pageId,
      itemId: itemId
    });
  },
  
  updateCnt: function(pageId, itemId, cnt) {
    new Request({
      url: '/c/storeCart/ajax_updateCnt'
    }).get({
      pageId: pageId,
      itemId: itemId,
      cnt: cnt
    });
  },
  
  clear: function(callback) {
    new Request({
      url: '/c/storeCart/ajax_clear',
      onComplete: function() {
        callback();
      }
    }).send();
  }
  
};

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
    this.initData();
  },
  
  count: 0,
  
  data: {},
  
  initData:  function() {
    this.restoreData();
    this.initCount();
    this.refrashElement();
    Ngn.cart.utils.initData(function(d) {
      if (d.length == 0) {
        this.data = {};
      } else {
        for (var i=0; i<d.length; i++) {
          d[i].cnt = parseInt(d[i].cnt);
          if (!this.data[d[i].pageId]) this.data[d[i].pageId] = {};
          this.data[d[i].pageId][d[i].itemId] = d[i].cnt;
          this.count += d[i].cnt;
        }
      }
      this.storeData();
      this.initCount();
      this.refrashElement();
    }.bind(this));
  },
  
  initCount: function() {
    var count = 0;
    for (var i in this.data)
      for (var j in this.data[i])
        count += this.data[i][j];
    this.count = count;
  },
  
  removeItem: function(pageId, itemId, reload) {
    delete this.data[pageId][itemId];
    Ngn.cart.utils.remove(pageId, itemId, function() {
      if (reload) window.location.reload(true);
    });
    this.storeData();
    this.initCount();
    this.refrashElement();
  },
  
  clear: function(reload) {
    this.data = {};
    Ngn.cart.utils.clear(function() {
      if (reload) window.location.reload(true);
    });
    this.update();
  },
  
  update: function() {
    this.storeData();
    this.initCount();
    this.refrashElement();
  },
  
  storeData: function() {
    Ngn.storage.json.set('cart', this.data);
  },
  
  restoreData: function() {
    this.data = Ngn.storage.json.get('cart') || {};
  },
  
  addItem: function(pageId, itemId) {
    if (!this.data[pageId]) this.data[pageId] = {};
    this.data[pageId][itemId] = this.data[pageId][itemId] ?
      this.data[pageId][itemId]+1 : 1;
    Ngn.cart.utils.add(pageId, itemId);
    this.update();
    this.showMove();
  },
  
  refrashElement: function() {
    this.eCaption.setStyle('display', this.count ? 'block' : 'none');
    this.eCount.set('html', this.count);
  },
  
  updateCnt: function(pageId, itemId, cnt) {
    Ngn.cart.utils.updateCnt(pageId, itemId, cnt);
    this.data[pageId][itemId] = cnt;
    this.update();
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
  Ngn.cart.block = new Ngn.cart.Block(el, options);
}; 
