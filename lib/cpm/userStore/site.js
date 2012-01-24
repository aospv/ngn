window.addEvent('domready', function() {
  var btnBuy = document.getElement('.f_buyBtn a.btn');
  if (!btnBuy) return;
  btnBuy.addEvent('click', function(e) {
    e.preventDefault();
    new Request({
      url: '/userStoreOrder/' + btnBuy.get('data-authorId') + '/ajax_add',
      onComplete: function() {
        window.location = '/userStoreOrder/' + btnBuy.get('data-authorId')
      }
    }).get({
      pageId: Ngn.site.page.id,
      itemId: Ngn.getParam(1)
    });
  });
});