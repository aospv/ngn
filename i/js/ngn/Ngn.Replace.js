Ngn.Replace = new Class({
  
  Extends: Ngn.Search,
  
  initialize: function(url, eSearchBtn, eReplaceBtn, eResults, eFrom, eTo,
                       eIsRegexp, searchAction, replaceAction) {
    this.parent(url, eSearchBtn, eResults, eFrom, searchAction);
    this.eTo = eTo;
    this.eIsRegexp = eIsRegexp;
    this.eReplaceBtn = eReplaceBtn;
    this.eReplaceBtn.setProperty('disabled', true);
    this.replaceAction = replaceAction;
    this.eTo.setProperty('value', this.settings.get('to'));
    this.eTo.addEvent('keypress', function(e) {
      if (e.key=='enter') {
        e.preventDefault();
        this._search();
      }
    }.bind(this));
    this.eReplaceBtn.addEvent('click', function(e) {
      e.preventDefault();
      if (this.searchInProgress) return;
      this._replace();
    }.bind(this));
    this.eIsRegexp.setProperty('checked', this.settings.get('isRegexp'));
    this.eIsRegexp.addEvent('change', function(e) {
      e.preventDefault();
      this.settings.set('isRegexp', this.eIsRegexp.getProperty('checked'));
    }.bind(this));
  },
  
  _search: function() {
    this.isRegexp = this.eIsRegexp.getProperty('checked') ? 1 : 0;
    this.to = this.eTo.getProperty('value');
    this.settings.set('to', this.to);
    this.eReplaceBtn.setProperty('disabled', true);
    this.parent();
  },
  
  _replace: function() {
    this.isRegexp = this.eIsRegexp.getProperty('checked') ? 1 : 0;
    this.mask = this.eMask.getProperty('value');
    if (!this.mask) {
      alert('Не введена маска поиска');
      return;
    }
    this.eResults.set('html', '');
    this.eResults.addClass('loader');
    this.eSearchBtn.addClass('disabled');
    this.searchInProgress = true;
    this.eReplaceBtn.setProperty('disabled', true);
    new Ngn.Request.JSON({
      url: this.url,
      onComplete: function(data) {
        this.searchInProgress = false;
        if (data.resultsCount) {
          this.eResults.set('html', '<p>Было произведено <b>'+data.resultsCount+'</b> замен</p>');
        } else {
          this.eResults.set('html', '<p>Ничего не найдено</p>');
        }
        this.settings.set('mask', this.mask);
        this.settings.set('to', this.to);
        this.eResults.removeClass('loader');
        this.eSearchBtn.removeClass('disabled');
      }.bind(this)
    }).POST(this.getReplacePostData());    
  },
  
  getPostData: function() {
    return {
      'action' : this.searchAction,
      'from' : this.mask,
      'to' : this.to,
      'isRegexp' : this.isRegexp
    };
  },
  
  getReplacePostData: function() {
    return {
      'action' : this.replaceAction,
      'from' : this.mask,
      'to' : this.to,
      'isRegexp' : this.isRegexp
    };    
  },
  
  searchSuccess: function() {
    this.eReplaceBtn.setProperty('disabled', false);
  }

});