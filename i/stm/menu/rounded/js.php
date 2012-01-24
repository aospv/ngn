window.addEvent('domready', function(){
  new Ngn.HorizontalMenuRounded($('menu'), {
    createExtraElements: false,
    borderElement: $('layout').getElement('.container'),
    roundBorderWidth: <?= (int)$d['roundBorderWidth'] ?>,
    openLeftOffset: 1,
    radius: <?= (int)$d['radius'] ?>,
  });
});