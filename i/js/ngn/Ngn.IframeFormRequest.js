Ngn.IframeFormRequest = new Class({

  Implements: [Options, Events],

  options: { /*
    onRequest: function() {},
    onComplete: function(data) {},
    onFailure: function() {}, */
    eventName: 'submit'
  },

  initialize: function(form, options) {
    this.setOptions(options);
    var frameId = this.frameId = String.uniqueID();
    var loading = false;

    this.form = document.id(form);

    this.formEvent = function() {
      loading = true;
      this.fireEvent('request');
    }.bind(this);

    this.iframe = new IFrame({
      name: frameId,
      styles: {
        display: 'none'
      },
      src: 'about:blank',
      events: {
        load: function() {
          if (loading) {
            var doc = this.iframe.contentWindow.document;
            if (doc && doc.location.href != 'about:blank') {
              this.complete(doc);
            } else {
              this.fireEvent('failure');
            }
            loading = false;
          }
        }.bind(this)
      }
    }).inject(document.body);
    this.attach();
  },
  
  complete: function(doc) {
    this.fireEvent('complete', doc.body.innerHTML);
  },

  attach: function() {
    this.target = this.form.get('target');
    this.form.set('action', this.form.get('action').toURI().setData({ifr: 1}, true).toString());
    this.form.set('target', this.frameId)
      .addEvent(this.options.eventName, this.formEvent);
  },

  detach: function() {
    this.form.set('target', this.target)
      .removeEvent(this.options.eventName, this.formEvent);
  }

});


Ngn.IframeFormRequest.JSON = new Class({
  Extends: Ngn.IframeFormRequest,
  
  initialize: function(form, options) {
    this.parent(form, options);
    this.iframe.responseType = 'json';
  },
  
  complete: function(doc) {
    var data = JSON.decode(doc.getElementById('json').value);
    if (data.error) {
      new Ngn.Dialog.Error({ error: data.error });
      return;
    }
    this.fireEvent('complete', data);
  }
  
});