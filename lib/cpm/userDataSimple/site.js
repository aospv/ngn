window.addEvent('domready', function() {
  Ngn.addBtnAction('.profileInfo .edit', function(eBtn) {
    Ngn.frm.makeDialogabble(
      eBtn,
      eBtn.hasClass('new') ? 'new' : 'edit'
    );
  }
});
