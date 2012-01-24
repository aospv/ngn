/*
if (ddItems) ddItems.getElements('.editBlock').each(function(el) {
    
    el.setStyle('width', el.getSize().x+'px');
    el.setStyle('position', 'absolute');
    var par = el.getParent();
    par.setStyle('position', 'relative');
    Ngn.setToTopRight(el, par, [10, 0]);
  });
*/
Ngn.site.DdItems = new Class({
  Implements: [Options],
  
  options: {
    curUserId: null,
    isAdmin: false,
    editPath: null,
    useUserId: false
  },
  
  initialize: function(esItems, options) {
    this.setOptions(options);
    if (!this.options.editPath) {
      this.options.editPath = Ngn.getPath(1);
    } else {
      this.options.useUserId = true;
    }
    this.editBlockTpl = Ngn.tpls.editBlock;
    esItems.each(function(eItem) {
      if (this.options.isAdmin || eItem.get('data-userId') == this.options.curUserId)
        this.addEditBlock(eItem);
    }.bind(this));
  },
  
  addEditBlock: function(eItem) {
    var eCont = eItem.getElement('.itemBody');
    var idParam = this.options.useUserId ? 'data-userId' : 'data-id';
    var eEditBlock =
      Ngn.strReplace('{path}', this.options.editPath,
      Ngn.strReplace('{id}', eItem.get(idParam),
        this.editBlockTpl)).toDOM()[0];
    eEditBlock.inject(eCont);
    eCont.setStyle('position', 'relative');
    Ngn.setToTopRight(eEditBlock, eCont, [10, 3]);
    eEditBlock.setStyle('width', eEditBlock.getSize().x+'px');
    eEditBlock.setStyle('position', 'absolute');
    var btnEdit = eEditBlock.getElement('.sm-edit');
    btnEdit.removeEvent('click');
    btnEdit.addEvent('click', function(e) {
      new Event(e).stop();
      new Ngn.Dialog.RequestForm({
        title: false,
        url: btnEdit.get('href').replace('edit', 'json_edit'),
        onSubmitSuccess: function() {
          window.location = window.location;
        }
      });
    });
    var btnDelete = eEditBlock.getElement('.sm-delete');
    btnDelete.addEvent('click', function(e) {
      new Event(e).stop();
      if (!confirm('Вы уверены?')) return;
      var eItem = eEditBlock.getParent('.item');
      var fx = new Fx.Morph(eItem, { duration: 'short', link: 'cancel' });
      fx.start({'opacity': [1, 0.4]});
      new Ngn.Request({
        url: btnDelete.get('href').replace('delete', 'ajax_delete'),
        onComplete: function() {
          fx.start({'opacity': [0.4, 0]});
          fx.addEvent('complete', function() {
            eItem.dispose();
          });
        }
      }).send();
    });
    
    var active = !eItem.hasClass('nonActive');
    var btnActivate = eEditBlock.getElement('.sm-activate');
    btnActivate.removeClass('sm-activate').addClass('sm-'+(active ? 'deactivate' : 'activate'));
    btnActivate.set('title', active ? 'Скрыть' : 'Показать');
    if (btnActivate) {
      btnActivate.addEvent('click', function(e) {
        new Event(e).stop();
        eItem.addClass('loading');
        new Ngn.Request({
          url: btnActivate.get('href').replace(
            'activate',
            active ? 'ajax_deactivate' : 'ajax_activate'
          ),
          onComplete: function() {
            eItem.removeClass('loading');
            if (active) {
              active = false;
              eItem.addClass('nonActive');
              btnActivate.removeClass('sm-deactivate').addClass('sm-activate');
              btnActivate.set('title', 'Показать');
            } else {
              active = true;
              eItem.removeClass('nonActive');
              btnActivate.removeClass('sm-activate').addClass('sm-deactivate');
              btnActivate.set('title', 'Скрыть');
            }
          }
        }).send();
      });
    }
    
  }
  
});

Ngn.site.DdItems.next = function(esItems, curId) {
  return Ngn.site.DdItems._next(esItems, curId, true);
};

Ngn.site.DdItems.prev = function(esItems, curId) {
  return Ngn.site.DdItems._next(esItems, curId, false);
};

Ngn.site.DdItems._next = function(esItems, curId, next) {
  for (var i=0; i<esItems.length; i++)
    if (esItems[i].get('data-id') == curId)
      if (next)
        return esItems[i+1] ? esItems[i+1] : esItems[0];
      else
        return esItems[i-1] ? esItems[i-1] : esItems[esItems.length-1];
};

Ngn.site.DdItems.EquailSizes = new Class({
  
  initialize: function(selector) {
    this.selector = selector ? selector : '.ddil_tile .ddItems .item';
    this.esItems = $$(this.selector);
    this.init();
  },
  
  init: function() {
    if (!this.esItems.length) return;
    this.cacheId = Ngn.getPath();
    var esImg = $$(this.selector+' img');
    var imgLinks = [];
    for (var i=0; i<esImg.length; i++) imgLinks.push(esImg[i].get('src'));
    new Asset.images(imgLinks, {
      onComplete: function(){
        Ngn.equalItemHeights(this.esItems);
      }.bind(this)
    });
  }
  
});
