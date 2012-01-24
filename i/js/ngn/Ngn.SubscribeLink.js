Ngn.SubscribeLink = new Class({

  Extends: Ngn.FlagLink,
  
  options: {
    classOn: 'sm-subscribed',
    classOff: 'sm-unsubscribed',
    authorized: false
  },
  
  initialize: function(el, key, options) {
    options.linkOn = window.location.pathname + '?action=ajax_subscribe' + key;
    options.linkOff = window.location.pathname + '?action=ajax_unsubscribe' + key;
    this.parent(el, options);
  },
  
  click: function() {
    if (!this.options.authorized) {
      var dialog = new Ngn.Dialog.Auth({
        url: '/c/authSubs',
        selectedTab: 1,
        onAuthComplete: function(obj) {
          new Request({
            url: this.options.linkOn,
            onComplete: function() {
              obj.authorize();
            }
          }).GET();
        }.bind(this)
      });      
      return;
    }
    this.parent();
  }

});