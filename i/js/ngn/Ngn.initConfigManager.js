Ngn.initConfigManager = function() {
  document.getElements('a[class~=delete]').each(function(el, n) {
    var name = el.get('title');
    el.set('title', 'Удалить');
    el.addEvent('click', function(e){
      new Event(e).stop();
      if (!confirm('Вы уверены?')) return;
      eValue = $('value_' + name2id(name).replace('k_', ''));
      eValue.addClass('loader');
      new Request({
        url: window.location.pathname,
        onComplete: function(data) {
        eValue.dispose();
        }.bind(this)
      }).GET({
        action: 'ajax_deleteValue',
        name: name
      });
      el.set('title', '');
    });
  });
};
