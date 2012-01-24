Ngn.site.singleImageNav = function(carousel) {
  var eSingleImageNav = new Element('div', {'class': 'arrowsNav'});
  var btnNext = Ngn.opacityBtn(new Element('a', {'class': 'next', title: 'следующее фото', href: '#'}), 0.6);
  btnNext.inject(eSingleImageNav);
  btnNext.addEvent('click', function(e) {
    e.preventDefault();
    window.location = Ngn.getPath(1)+'/'+
      Ngn.site.DdItems.next(carousel.esItems, Ngn.getParam(1)).get('data-id');
  }.bind(this));
  var btnPrev = Ngn.opacityBtn(new Element('a', {'class': 'prev', title: 'предыдущее фото', href: '#'}), 0.6);
  btnPrev.inject(eSingleImageNav);
  btnPrev.addEvent('click', function(e) {
    e.preventDefault();
    window.location = Ngn.getPath(1)+'/'+
      Ngn.site.DdItems.prev(carousel.esItems, Ngn.getParam(1)).get('data-id');
  }.bind(this));
  var eParent = $('layout').getElement('.oneItem .item');
  eSingleImageNav.inject(eParent, 'top');
  eParent.setStyle('display', 'none');
  Asset.image(eParent.getElement('img').get('src'), {
    onLoad: function() {
      eParent.setStyle('display', 'block');
      Ngn.setToCenterRelHor(eParent.getElement('.thumb'), eParent);
      Ngn.setToCenterRight(btnNext, eParent, [10, 0]);
      Ngn.setToCenterLeft(btnPrev, eParent, [10, 0]);
    }
  });
};
