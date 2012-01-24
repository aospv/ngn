Ngn.iconBtn2 = function(cssClass, title, action) {
  var eBtn = ('<a href="#" class="'+cssClass+'"><i></i>'+title+'</a>').toDOM()[0];
  eBtn.addEvent('click', function(e) {
    e.preventDefault();
    action();
  });
  return eBtn;
};


Ngn.regNamespace('Ngn.site.top.briefcase.btns', true);

Ngn.site.top.briefcase.btns.unshift(['users', 'Профиль', function() {
  window.location = Ngn.tpls.userPath.replace('{id}', this.userId);
}]);

Ngn.site.top.briefcase.Menu = new Class({

  Implements: [Options],

  initialize: function(userId, options) {
    this.setOptions(options);
    this.userId = userId;
    var obj = this;
    new Ngn.DropdownWin($('myLogin'), {
      winClass: 'briefcaseWin',
      onDomReady: function() {
        var eCont = '<div class="iconsSet"></div>'.toDOM()[0];
        for (var i=0; i<Ngn.site.top.briefcase.btns.length; i++) {
          var btn = Ngn.site.top.briefcase.btns[i];
          Ngn.iconBtn2(btn[0], btn[1], btn[2].bind(obj)).inject(eCont);
        }
        eCont.inject(this.eBody)
      }
    });
  }
  
});

/*
'<a href="<?= Tt::getUserPath(Auth::get('id')) ?>" class="users"><i></i>Инфо</a>'+
'<a href="#" class="profile"><i></i>Изменить профиль</a>'+
'<a href="#" class="settings"><i></i>Настройки аккаунта</a>'+
'<a href="#" class="cart"><i></i>Параметры магазина</a>'+
*/