window.addEvent('domready', function() {
  var subNav, subNavH, mceToolbarH, ed, iframe, btnSubmit, processing, form, actionUrl, resize, mc;
  mc = $('mainContent');
  if (!mc.hasClass('a_editContent') || !mc.hasClass('am_pages')) return;
  subNav = $('subNav');
  btnSubmit = '<a href="" class="save"><i></i><b>Сохранить</b></a>'.toDOM()[0].
    inject(subNav.getElement('.navSubBtns'), 'top');
  processing = false;
  form = $('mainContent').getElement('.apeform form');
  actionUrl = form.getAttributeNode('action').nodeValue.replace(/(.*a=)(\w+)/g, '$1json_$2');
  resize = function() {
    if (!iframe) return;
    iframe.setStyle('height',
      Ngn.cp.getMainAreaHeight()-subNavH-mceToolbarH-1);
  };
  (function() {
    ed = tinyMCE.get('textarea0');
    iframe = ed.contentAreaContainer.getElement('iframe');
    iframe.focus();
    mceToolbarH = $('textarea0_tbl').getElement('.mceToolbar').getSize().y;
    subNavH = subNav.getSize().y;
    resize();
  }).delay(1000);
  btnSubmit.addEvent('click', function(e) {
    e.preventDefault();
    btnSubmit.addClass('nonActive');
    if (processing) return;
    ed.setProgressState(1);
    processing = true;
    new Request.JSON({
      url: actionUrl,
      onComplete: function(r) {
        btnSubmit.removeClass('nonActive');
        processing = false;
        ed.setProgressState(0);
        ed.setContent(r.text);
      }
    }).post({
      formId: form.get('id'),
      text: ed.getContent()
    });
  });
  window.addEvent('resize', function() {
    resize();
  });
});
