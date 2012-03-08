window.addEvent('domready', function(){
  new Ngn.HorizontalMenu($('menu'), {
    borderElement: $('layout').getElement('.container')
  });
});