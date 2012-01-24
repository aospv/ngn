Ngn.frm.ConsecutiveSelect = new Class({
  
  initialize: function(selector, strName) {
    this.selector = selector;
    this.strName = strName;
    this.init();
  },
  
  init: function() {
    $$(this.selector).each(function(el, i){
      el.getElements('select').each(function(el, i){
        el.removeEvents('change');
        el.addEvent('change', function(e){
          this.loadSelect(el);
        }.bind(this));
      }.bind(this));
    }.bind(this));
  },
  
  loadSelect: function(eChangedSelect) {
    if (!eChangedSelect.get('value')) return;
    while (next = eChangedSelect.getNext()) next.dispose(); // убираем стрелочку
    var eRow = eChangedSelect.getParent('.element');
    eRow.addClass('hLoader');
    new Request({
      url: '/c/ddTagsConsecutiveSelect/' + this.strName,
      onComplete: function(html) {
        eRow.removeClass('hLoader');
        if (!html) return;
        new Element('span', {html: html}).inject(eChangedSelect, 'after');
        this.init();
      }.bind(this)
    }).GET({
      name: eChangedSelect.get('name'),
      id: eChangedSelect.get('value')
    });
  }
  
});