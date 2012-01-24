Ngn.Items = new Class({
  
  Implements: [Options, Events],
  
  options: {
    idParam: 'id',
    mainElementSelector: '.mainContent',
    itemsElementId: 'items',
    itemElementSelector: '.item',
    deleteAction: 'delete',
    isSorting: false,
    itemsLayout: 'details'
  },
  
  initialize: function(options) {
    this.setOptions(options);
    this.options.itemDoubleParent = this.options.itemsLayout == 'tile' ? false : true;
    this.init();
    return this;
  },
  
  getId: function(eItem) {
    return eItem.get('id').split('_')[1];
  },
  
  init: function() {
    this.eItems = $(this.options.itemsElementId);
    this.esItems = this.eItems.getElements(this.options.itemElementSelector);
    for (var i=0; i<this.esItems.length; i++)
      this.esItems[i].store('itemId', this.getId(this.esItems[i]));
    //////////////// Delete ///////////////
    this.eItems.getElements('a[class~=delete]').each(function(el, i){
      el.addEvent('click', function(e){
        new Event(e).stop();
        if (!confirm('Вы уверены?')) return;
        var eItem = el.getParent().getParent();
        var eLoading = eItem;
        eLoading.addClass('loading');
        var g = {};
        g[this.options.idParam] = eItem.retrieve('itemId');
        new Request({
          url: window.location.pathname + '?a=ajax_' + this.options.deleteAction,
          onComplete: function() {
            this.fireEvent('deleteComplete');
            eItem.destroy();
          }.bind(this)
        }).GET(g);
      }.bind(this));
    }.bind(this));
    ////////////// Activate ///////////////
    this.eItems.getElements('a[class$=activate]').each(function(el, i){
      el.addEvent('click', function(e){
        new Event(e).stop();
        var eItem = el.getParent().getParent();
        var eLoading = eItem;
        var active = !el.get('class').test('deactivate');
        eLoading.addClass('loading');
        var post = {};
        post[this.options.idParam] = eItem.get('id').split('_')[1];
        new Request({
          url: window.location.pathname + '?a=ajax_' + (active ? 'deactivate' : 'activate'),
          onComplete: function() {
            active ? eItem.addClass('nonActive') : eItem.removeClass('nonActive');
            if (active) {
              el.removeClass('activate');
              el.addClass('deactivate');  
            } else {
              el.removeClass('deactivate');
              el.addClass('activate');
            }
            this.fireEvent('activeChangeComplete');
            eLoading.removeClass('loading');
          }.bind(this)
        }).GET(post);
      }.bind(this));
    }.bind(this));
    ///////////////// Flags //////////////////
    this.eItems.getElements('a[class~=flagOn],a[class~=flagOff]').each(function(el, i) {
      var eFlagName = el.getElement('i');
      var flagName = eFlagName.get('title');
      eFlagName.removeProperty('title');
      el.addEvent('click', function(e){
        var flag = el.get('class').match(/flagOn/) ? true : false;
        new Event(e).stop();
        var eItem = this.options.itemsLayout == 'tile' ? el.getParent() : el.getParent().getParent();
        var eLoading = eItem;
        eLoading.addClass('loading');
        var post = {};
        post[this.options.idParam] = eItem.get('id').split('_')[1];
        post['k'] = flagName;
        post['v'] = flag ? 0 : 1;
        new Request({
          url: window.location.pathname + '?a=ajax_updateDirect',
          onComplete: function() {
            el.removeClass(flag ? 'flagOn' : 'flagOff');
            el.addClass(flag ? 'flagOff' : 'flagOn');
            eLoading.removeClass('loading');
          }
        }).GET(post);
      }.bind(this));
    }.bind(this));
  },
  
  addBtnAction: function(eItem, btnSelector, action) {
    var eBtn = eItem.getElement(btnSelector);
    if (!eBtn) return;
    eBtn.addEvent('click', function(e){
      new Event(e).stop();
      action(eItem.retrieve('itemId'), eBtn);
    }.bind(this));
  },
  
  addBtnsActions: function(actions) {
    this.esItems.each(function(eItem) {
      for (var i=0; i<actions.length; i++) {
        this.addBtnAction(eItem, actions[i][0], actions[i][1]);
      }
    }.bind(this));
  },
  
  reload: function() {
    //Ngn.loading(true);
    new Request({
      url: window.location.pathname + '?a=ajax_reload',
      onComplete: function(html) {
        this.eItems.empty();
        this.eItems.set('html', html);
        this.init();
        Ngn.cp.initTooltips();
        //Ngn.loading(false);
        this.fireEvent('reloadComplete');
      }.bind(this)
    }).send();
  }

});