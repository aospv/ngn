Ngn.DdItemsGroup = new Class({
  
  initialize: function(eItems) {
    this.eSubNav = $('subNav');
    
    this.esCheckboxes = document.id(eItems).getElements('input[type=checkbox]');
    if (!this.esCheckboxes.length) return;
    this.eItemsForm = $('itemsForm');
    this.eCheckAll = $('checkAll');

    // --------- Общее для всех групповых кнопок -----------
    this.esCheckboxes.each(function(el){
      el.addEvent('change', function() {
        this.toggleGroupBtns();
      }.bind(this));
    }.bind(this));
    this.toggleGroupBtns();
    // ------------------------------------------------------

    this.addSubNavBtnAction('.move', function() {
      if (!Ngn.checkboxesSelected(this.esCheckboxes)) return;
      this.eItemsForm.set('action', this.eBtnDeleteGroup.get('href'));
      this.eItemsForm.submit();
    }.bind(this));
    
    this.addSubNavBtnAction('.deleteGroup', function() {
      if (!confirm('Вы уверены?')) return;
      if (!Ngn.checkboxesSelected(this.esCheckboxes)) return;
      new Ngn.Dialog.Loader.Simple({title: 'Идёт удаление. Подождите'});
      new Request({
        url: Ngn.getPath() + '?a=ajax_deleteGroup&' +
          Hash.toQueryString(Ngn.frm.toObj(this.eItemsForm)),
        onComplete: function() {
          (function() {
            window.location.reload(true);
          }).delay(500);
        }
      }).send();
    }.bind(this));
    
    this.addSubNavBtnAction('.users', function() {
      if (!Ngn.checkboxesSelected(this.esCheckboxes)) return false;
      this.eItemsForm.set('action', this.eBtnDeleteGroup.get('href'));
      this.eItemsForm.submit();
    }.bind(this));
    
    this.addSubNavBtnAction('.select', function() {
      if (this.eCheckAll) this.eCheckAll.set('checked', true);
      this.checkAll(true);
    }.bind(this));

    if (this.eCheckAll) {
      this.eCheckAll.addEvent('change', function() {
        this.checkAll(this.eCheckAll.get('checked'));
      }.bind(this));
    }
  
  },
  
  addSubNavBtnAction: function(selector, action) {
    var btn = this.eSubNav.getElement(selector);
    if (!btn) return;
    btn.setStyle('display', 'block');
    btn.addEvent('click', function(e) {
      new Event(e).stop();
      action();
    });
  },

  toggleGroupBtns: function() {
    var selected = Ngn.checkboxesSelected(this.esCheckboxes);
    this.toggleGroupBtn('.move', selected);
    this.toggleGroupBtn('.users', selected);
    this.toggleGroupBtn('.deleteGroup', selected);
  },
  
  toggleGroupBtn: function(selector, selected) {
    var btn = this.eSubNav.getElement(selector);
    if (!btn) return;
    selected ? btn.removeClass('nonActive') : btn.addClass('nonActive');
  },

  checkAll: function(flag) {
    this.esCheckboxes.each(function(el){
      el.set('checked', flag);
    });
    this.toggleGroupBtns();
  }
  
});