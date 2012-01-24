window.addEvent('domready', function() {
  if (!$('layout').hasClass('action_showItem')) return;
  var carousel = new Ngn.site.photoCarousel();
  Ngn.site.singleImageNav(carousel);
});
