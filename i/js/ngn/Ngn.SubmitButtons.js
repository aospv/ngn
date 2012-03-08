Ngn.SubmitButtons = new Class({
  
  initialize: function() {
    document.getElements('form').each(function(el){
      this.initForm(el);
    }.bind(this));
  },
  
  initForm: function(eForm) {
    return;
    
    var eBtn = eForm.getElement('input[type=submit]');
    if (!eBtn) return;
    eForm.submit = function() {
      eForm.fireEvent('submit', eForm);
      eForm._submit();
    }
    var submiting = false;
    eForm.addEvent('submit', function(e) {
      if (submiting) return false;
      submiting = true;
      eBtn.disabled = true;
      eBtn.addClass('loading');
    });
    eForm._submit = eForm.submit;
    /*
    eBtn.addEvent('click', function(e){
      eForm.submit();
      return false;
    });
    */
  }
  
});

Ngn.SubmitButtons2 = {
  
  initForm: function(eForm) {
    var eBtn = eForm.getElement('a.btnSubmit');
    if (!eBtn) return;
    /*
    var submiting = false;
    eForm._submit = eForm.submit;
    eForm.submit = function() {
      eForm.fireEvent('submit', eForm);
      eForm._submit();
    }
    eForm.addEvent('submit', function(e) {
      if (submiting) return false;
      submiting = true;
      eBtn.addClass('loading');
    });
    */    
    eBtn.addEvent('click', function(e){
      e.preventDefault();
      eForm.submit();
    });
  }
  
}