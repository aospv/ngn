Ngn.regNamespace('Ngn.site.top.auth');

Ngn.site.top.auth.init = function(options) {
  Ngn.site.top.auth.exists = true;
  Ngn.site.top.auth.options = options;
  new Ngn.site.top.Auth(options);
};

Ngn.site.top.auth.reload = function() {
  if (!Ngn.site.top.auth.exists) return;
  $('top').load('/c/auth/ajax_top?path='+Ngn.getPath());
  new Ngn.site.top.Auth(Ngn.site.top.auth.options);
};

Ngn.site.top.Auth = new Class({
  
  Implements: [Options],
  
  options: {
    privMsgsPath: '',
    searcherPath: ''
  },
  
  initialize: function(options) {
    this.setOptions(options);
    // Названия внутри текстовых полей
    Ngn.site.authFormDefaults(
      $('authForm'), $('authLogin'), $('authPass'), $('btnLogin'), 'Логин', 'Пароль');
      
    // Подсказка при авторизации
    var eTopSlideTip = $('topSlideTip');
    if (eTopSlideTip) {
      var st = (eTopSlideTip.get('class') == 'topSlideTipPm' && this.options.privMsgsPath) ?
        this.getPmSlideTips() : new Ngn.SlideTips(eTopSlideTip);
      st.setOptions({
        onShow: function() {
          eTopSlideTip.setStyle(
            'left',
            -Math.round(this.element.getSize().x / 2 - this.element.getPrevious().getSize().x / 2)
          );
        }
      });
    }
    if (this.options.searcherPath) this.initSearcher();
    // Форма потеряного пароля
    if (this.options.errors && this.options.errors[0].code == 2) new Ngn.LostPassForm();
  },
  
  getPmSlideTips: function() {
    new Ngn.SlideTips.Pm(eTopSlideTip, {
      url: this.options.privMsgsPath,
      onRemove: function() {
        var n = this.getTotal();
        var ePmCnt = $('pmCnt');
        $('pmCnt').set('html', n);
        if (!n) {
          var ePmBtn = ePmCnt.getParent().getParent();
          ePmBtn.removeClass('send2');
          ePmBtn.removeClass('notify2');
          ePmBtn.addClass('sendOff');
          ePmCnt.getParent().dispose();
        }
      },
      onSystem: function(system) {
        var ePmBtn = $('pmCnt').getParent().getParent();
        if (system) {
          ePmBtn.removeClass('send2');
          ePmBtn.addClass('notify2');
        } else {
          ePmBtn.removeClass('notify2');
          ePmBtn.addClass('send2');
        }
      }
    });
  },
  
  initSearcher: function() {
    // Поиск
    var eSearch = $('fldSearch');
    if (eSearch) {
      var defSearchValue = this.s ? this.s : 'поиск...';
      eSearch.addEvent('focus', function(e) {
        if (eSearch.get('value') == defSearchValue) eSearch.set('value', '');
      });
      eSearch.addEvent('blur', function(e) {
        if (eSearch.get('value') == '') eSearch.set('value', defSearchValue);
      });
      eSearch.set('value', defSearchValue);
      eSearch.addEvent('keydown', function(e) {
        if (e.key=='enter') {
          if (!eSearch.get('value')) return;
          window.location = this.options.searcherPath + '?s=' + eSearch.get('value');
        }
      });
      $('btnSearch').addEvent('click', function(e){
        e.preventDefault();
        if (!eSearch.get('value')) return;
        window.location = this.options.searcherPath + '?s=' + eSearch.get('value');
      });
    }
  }
  
});