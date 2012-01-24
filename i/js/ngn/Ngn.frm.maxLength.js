Ngn.frm.maxLength = function(eForm, defaultMaxLength) {
  eForm.getElements('textarea').each(function(eInput){
    var maxlength = eInput.get('maxlength');
    var init = function() {
      eRemained.set('html',
       ' (осталось ' + (maxlength-eInput.get('value').length) + ' знаков из ' + maxlength + ')'
      );
    };
    if (maxlength >= defaultMaxLength) return;
    var eRemained = new Element('small', {
      'class': 'remained gray'
    }).inject(eInput.getParent('.element').getElement('.label'), 'bottom');
    eInput.addEvent('keyup', init);
    init();
  });
};