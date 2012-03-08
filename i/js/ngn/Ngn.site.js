Ngn.site = {
  authFormDefaults: function(eForm, eLogin, ePass, eEnter, defLogin, defPass) {
    if (!eForm) return;

    // //////////////////////////////////////
    eLogin.addEvent('keydown', function(e) {
      if (e.key=='enter' && e.control) {
        if (!eLogin.get('value')) return;
        eForm.submit();                
      }
    });
    ePass.addEvent('keydown', function(e) {      
      if (e.key=='enter') {
        if (!eLogin.get('value')) return;
        eForm.submit();                
      }
    });
    eLogin.addEvent('focus', function(e) {
      if (eLogin.get('value') == defLogin) eLogin.set('value', '');
    });
    eLogin.addEvent('blur', function(e) {
      if (eLogin.get('value') == '') eLogin.set('value', defLogin);
    });
    ePass.addEvent('focus', function(e) {
      if (ePass.get('value') == defPass) ePass.set('value', '');
    });
    ePass.addEvent('blur', function(e) {
      if (ePass.get('value') == '') ePass.set('value', defPass);
    });            
    eEnter.addEvent('click', function(e) {
      e.preventDefault();      
      eForm.submit();                
    });
    eLogin.set('value', defLogin);
    ePass.set('value', defPass);
  }
  
};
