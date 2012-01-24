Ngn.frm.SaverBase = new Class({
  Implements: [Options],
  
  options: {
    // url: '',
    changeElementsSelector: Ngn.frm.selector,
    jsonRequest: false
  },
  
  saving: false,
  
  initialize: function(eForm, options) {
    this.setOptions(options);
    this.eForm = eForm;
    this.hash = JSON.encode(Ngn.frm.toObj(this.eForm));
    this.addEvents();
    this.init();
  },
  
  init: function() {},
  
  addEvents: function() {
    var els = this.eForm.getElements(this.options.changeElementsSelector);
    els.each(function(eInput) {
      if (eInput.retrieve('saver')) return;
      eInput.store('saver', true);
      eInput.addEvent('blur', this.save.bind(this));
      eInput.addEvent('change', this.save.bind(this));
    }.bind(this));
  },
  
  save: function() {
    if (this.saving) return;
    var p = Ngn.frm.toObj(this.eForm);
    var postHash = JSON.encode(p);
    if (postHash == this.hash) return;
    Ngn.loading(true);
    this.saving = true;
    Ngn.frm.disable(this.eForm, true);
    new (this.options.jsonRequest ? Request.JSON : Request)({
      url: this.options.url,
      onSuccess: function(r) {
        this.saving = false;
        this.hash = postHash;
        Ngn.loading(false);
        Ngn.frm.disable(this.eForm, false);
      }.bind(this)
    }).post(p);
  }
  
});

Ngn.frm.Saver = new Class({
  Extends: Ngn.frm.SaverBase,
  
  init: function() {
    for (var i=0; i<Ngn.frm.fieldSets.length; i++) {
      Ngn.frm.fieldSets[i].addEvent('delete', this.save.bind(this));
      Ngn.frm.fieldSets[i].addEvent('cleanup', this.save.bind(this));
      Ngn.frm.fieldSets[i].addEvent('addRow', this.addEvents.bind(this));
    }
  }
  
});