Ngn.msgs.Actions = new Class({
  
  initialize: function(url) {
    this.url = window.location.href;
  },
  
  
  // Ngn.msgs.MsgLayout
  
  /**
   * @param {number}
   * @param {Ngn.msgs.MsgLayout}
   */
  getText: function(id, objMsg) {
    objMsg.elMsg.addClass('loading');
    new Request({
      url: this.url,
      onComplete: function(text) {
        this.getTextComplete(text, objMsg);
      }.bind(this)
    }).GET({
      'sub' : 'msgs',
      'action' : 'sub_ajax_getText',
      'id' : id
    });
  },
  
  getTextComplete: function(text, objMsg) {
    objMsg.elMsg.removeClass('loading');
    objMsg.switchEdit(text);
  },
  
  updateText: function(id, text, objMsg) {
    objMsg.elMsg.addClass('loading');
    new Request({
      url: this.url,
      onComplete: function(text) {
        this.updateTextComplete(text, objMsg);
      }.bind(this)
    }).POST({
      'sub' : 'msgs',
      'action': 'sub_ajax_update',
      'id': id,
      'text': text
    });
  },
  
  updateTextComplete: function(text, objMsg) {
    objMsg.elMsg.removeClass('loading');
    if (text) objMsg.switchView(text);
  },
  
  _create: function(text) {
    new Request.JSON({
      url: this.url,
      onComplete: function(data) {
        this.createComplete(data);
      }.bind(this)
    }).get({
      'sub' : 'msgs',
      'action': 'sub_json_create',
      'text': text
    });
  },
  
  activate: function(id, objMsg) {
    this._activate(id, objMsg, 'activate');
  },
  
  deactivate: function(id, objMsg) {
    this._activate(id, objMsg, 'deactivate');
  },
  
  _activate: function(id, objMsg, word) {
    new Request({
      url: this.url,
      onComplete: function(text) {
        this._activateComplete(objMsg, word);
      }.bind(this)
    }).GET({
      'sub' : 'msgs',
      'action': 'sub_ajax_'+word,
      'id': id
    });
  },
  
  _activateComplete: function(objMsg, word) {
    eval('objMsg.'+word+'();');
    window.location = this.url;
  },
  
  _delete: function(id, objMsg) {
    objMsg.elMsg.addClass('loading');
    new Request({
      url: this.url,
      onComplete: function(data) {
        this.deleteComplete(objMsg, data);
      }.bind(this)
    }).GET({
      'sub' : 'msgs',
      'action': 'sub_ajax_delete',
      'id': id
    });
  },
  
  deleteComplete: function(objMsg, data) {
    objMsg.elMsg.removeClass('loading');
    new Fx.Slide(objMsg.elMsg, {
      duration: 1000,
      transition: Fx.Transitions.Pow.easeOut,
      onComplete: function() {
        objMsg.elMsg.destroy();
      }
    }).slideOut();
  }
  
});