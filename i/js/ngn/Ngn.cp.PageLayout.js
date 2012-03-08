Ngn.cp.PageLayout = new Class({
  
  Implements: [Options],
  
  //options: {
  //globalBlocksExists: true
  //},
  
  initialize: function(eLayouts, options) {
    this.setOptions(options);
    this.eLayouts = eLayouts;
    this.eLayouts.getElements('.item a').each(function(el){
      if (el.hasClass('sel'))
        this.eSel = el;
      el.addEvent('click', function(e) {
        e.preventDefault();
        if (this.change(el.get('data-n'))) {
          if (this.eSel) this.eSel.removeClass('sel');
          el.addClass('sel');
          this.eSel = el;
        }
      }.bind(this));
    }.bind(this));
    this.curLayoutN = this.eSel.get('data-n');
  },
  
  finalQuestion: 'Вы действительно хотите изменить настройки?',
  inProgess: false,
  
  change: function(layoutN) {
    if (this.inProgess) return false;
    if (layoutN == this.curLayoutN) return false;
    
    if (
      (this.curLayoutN == 4 || this.curLayoutN == 5) &&
      (layoutN == 2 || layoutN == 3)
    ) {
      if (!confirm('Блоки из серых колонок будут перенесены в одну. ' + 
      this.finalQuestion))
        return false;
    }
    else if (
      (this.curLayoutN == 6) &&
      (layoutN == 4 || layoutN == 5)
    ) {
      if (!confirm('Блоки из третьей колонки будут перенесены во вторую серую колонку. ' + 
      this.finalQuestion))
        return false;
    }
    else if (
      (this.curLayoutN == 7) &&
      (layoutN == 4 || layoutN == 5)
    ) {
      if (!confirm('Блоки из третьей и четвертой колонок будут перенесены в первую и вторую серые колонки соответственно. ' + 
      this.finalQuestion))
        return false;
    }
    else if (
        (this.curLayoutN == 6) &&
        (layoutN == 2 || layoutN == 3)
      ) {
        if (!confirm('Блоки из третьей колонки будут перенесены в первую серую колонку. ' + 
        this.finalQuestion))
          return false;
      }
    else if (
      (this.curLayoutN == 7) &&
      (layoutN == 2 || layoutN == 3)
    ) {
      if (!confirm('Блоки из второй, третьей и четвертой колонок будут перенесены в одну серую колонку. ' + 
      this.finalQuestion))
        return false;
    }
    else if (this.curLayoutN == 7 && layoutN == 6) {
      if (!confirm('Блоки из четвертой колонки будут перенесены в третью колонку. ' + 
      this.finalQuestion))
        return false;
    }
    
    if (
      (layoutN == 1 || layoutN == 6 || layoutN == 7) &&
      (this.curLayoutN == 2 || this.curLayoutN == 3 ||
       this.curLayoutN == 4 || this.curLayoutN == 5)
    ) {
      if (!confirm('Все глобальные блоки будут удалены. ' + this.finalQuestion))
        return false;
    }     
    
    Ngn.loading(true);
    this.inProgess = true;
    new Request({
      url: window.location.pathname,
      data: {
        action: 'ajax_updateLayoutN',
        layoutN: layoutN
      },
      onComplete: function(data) {
        this.inProgess = false;
        this.curLayoutN = layoutN;
        Ngn.loading(false);
      }.bind(this)
    }).GET();
    
    return true;
  }
  
});