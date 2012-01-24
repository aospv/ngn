Ngn.Atlas = new Class({
  
  Extends: Atlas,
  
  options: {
    disposeElementSelecor: 'b', 
    zoom: 10,
    type: 'G_HYBRID_MAP',
    city: 'Нижний Новгород',
    additionalClass: 'addressMap'
  },
  
  init: function(eAddress) {
    var eB = eAddress.getElement(this.options.disposeElementSelecor);
    eAddress.addClass(this.options.additionalClass);
    if (eB) eB.dispose();
    if (!this.options.search)
        this.options.search = this.options.city + ', ' + eAddress.get('text').trim();
    this.parent(eAddress);
  }

});