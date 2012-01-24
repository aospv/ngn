Ngn.initPopups = function() {
  document.getElements('a.popup').each(function(eLink, n) {
    eLink.addEvent('click', function() {
      new Ngn.Dialog.Alert({
        force: true,
        title: 'FUCKOFF',
        eLink.get('href'),
        width: '50'
      });
    });
  });
}
