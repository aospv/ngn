Ngn.PageBlockCreate = new Class({
  initialize: function() {
    this.eMethod = $('method');
    this.initPageSelect();
    this.initMethodSelect();
  },
  getPageId: function() {
    var eFldPageId = $('fld-pageId');
    if (eFldPageId) var pageId = $('fld-pageId').get('value');
    return pageId ? pageId : 0;
  },
  initPageSelect: function() {
    Ngn.Autocompleters.init();
    Ngn.Autocompleters.ac['pageId'].addEvent('selection', function() {
      new Request({
        url: window.location.pathname,
        onComplete: function(html) {
          this.eMethod.set('html', html);
          
          this.initMethodSelect();
        }.bind(this)
      }).GET({
        action: 'ajax_pageBlockSelectMethod',
        pageId: this.getPageId()
      })
    }.bind(this));
  },
  initMethodSelect: function() {
    var eMethodSelect = this.eMethod.getElement('select');
    eMethodSelect.addEvent('change', function() {
      new Request({
        url: window.location.pathname,
        onComplete: function(html) {
          var eMethodProp = $('methodProp');
          eMethodProp.set('html', html);
        }
      }).GET({
        action: 'ajax_pageBlockMethodProp',
        pageId: this.getPageId(),
        method: eMethodSelect.get('value')
      });
    }.bind(this));
  }  
});