Ngn.RequestFieldsSelect = new Class({
  
  initialize: function(eSelectContainer, options) {
    if (!options.url) options.url = Ngn.getPath();
    this.eSelect = eSelectContainer.getElement('select');
    var eRequestFieldsContainer = new Element('div', {'class': 'requestFields'}).
      inject(eSelectContainer, 'after');
    this.eSelect.addEvent('change', function() {
      eSelectContainer.addClass('loader2');
      new Request({
        url: options.url+'?a='+options.action+'&'+this.eSelect.get('name')+'='+this.eSelect.get('value'),
        onComplete: function(html) {
          eSelectContainer.removeClass('loader2');
          eRequestFieldsContainer.empty();
          eRequestFieldsContainer.adopt(html.toDOM());
        }
      }).send();
    }.bind(this));
  }
  
});