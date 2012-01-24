Ngn.GoogleMap = new Class({
  
  Implements: [Options],
  
  options: {
    defaultCity: null
  },
  
  initialize: function(eAddress, options) {
    if (!Ngn.googleMapKey) throw new Error('Ngn.googleMapKey is empty');
    this.setOptions(options);
    var eB = eAddress.getElement('b');
    if (eB) eB.dispose();
    var address = eAddress.get('text').trim();
    var eMap = new Element('div', {
      // Убрать стили отсюда !!!
      styles: {
        'width': '500px',
        'height': '200px',
        'margin-bottom': '10px'
      }
    });
    eMap.replaces(eAddress);
    new Atlas(eMap, {
      key: this.options.googleMapKey,
      zoom: 10,
      type: 'G_HYBRID_MAP',
      search: (defaultCity ? defaultCity + ', ' : '') + address
    });
  }
  
});