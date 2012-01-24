Ngn.frm.stmEditFieldsSaver = function(_opt) {
  var opt = {
    formId: 'Form',
    fancyUploadAction: 'json_fancyUpload'
  };
  $extend(opt, _opt);
  var form = Ngn.Form.forms[opt.formId];
  form.eForm.getElements('.type_fieldSet .element').each(function(el){
    el.addClass('subElement');
  });
  if (opt.useSaver) {
    var saver = new Ngn.frm.Saver(form.eForm, {
      url: Ngn.getPath() + '?a=' + opt.updateAction
    });
    if ($defined(Ngn.frm.fieldSets)) {
      for (i=0; i<Ngn.frm.fieldSets.length; i++) {
        Ngn.frm.fieldSets[i].addEvent('delete', function(){
          saver.save();
        });
      }
    }
  }
  form.initFancyUpload({
    url: Ngn.getPath(5) + '?a=' + opt.fancyUploadAction + '&fn={fn}&sessionId=' + opt.sessionId, 
    loadedFiles: [],
    chooseBtnTitle: 'Выбрать',
    hideHelp: true
  });
};
