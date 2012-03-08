Ngn.Search = new Class({
  
  initialize: function(url, name, searchAction) {
    this.url = url;
    this.name = name;
    this.searchAction = searchAction;
    
    this.eSearchBtn = $(name + 'SearchBtn');
    this.eResults = $(name + 'Results');
    this.eMask = $(name + 'Mask');
    this.eSearchAllBtn = $(name + 'SearchAllBtn');
    
    this.searchInProgress = false;
    //this.settings = new Hash.Cookie('set'+this.name, {duration: 3600});
    //this.eMask.setProperty('value', this.settings.get('mask'));
    this.eSearchBtn.addEvent('click', function(e) {
      e.preventDefault();
      if (this.searchInProgress) return;
      this._search();
    }.bind(this));
    this.eSearchAllBtn.addEvent('click', function(e) {
      e.preventDefault();
      if (this.searchInProgress) return;
      this.eMask.setProperty('value', '%');
      this._search();
    }.bind(this));
    this.eMask.addEvent('keypress', function(e) {
      if (e.key=='enter') {
        e.preventDefault();
        this._search();
      }
    }.bind(this));  
  },
  
  _search: function() {
    this.mask = this.eMask.getProperty('value');
    if (!this.mask) {
      //alert('Не введена маска поиска');
      return;
    }   
    //this.settings.set('mask', this.mask);    
    this.eResults.set('html', '');
    this.eResults.addClass('loader');
    this.eSearchBtn.addClass('disabled');
    this.searchInProgress = true;
    new Ngn.Request.JSON({
      url: this.url,
      onComplete: function(data) {
        this.searchInProgress = false;
        this.eResults.removeClass('loader');
        this.eSearchBtn.removeClass('disabled');
        if (!data) {
          this.eResults.set('html', 'Неверный запрос');
          return;
        }
        if (data.error) this.eResults.set('html', data.error);
        else this.eResults.set('html', data.html);        
        if (data.resultsCount) this.searchSuccess();
      }.bind(this)
    }).GET(this.getPostData());
  },
  
  getPostData: function() {
    return {
      'action' : this.searchAction,
      'name' : this.name,
      'mask' : this.mask
    };
  },
  
  searchSuccess: function() {
  }
  
});

