window.addEvent('domready', function() {
  (function() {
    if (!Ngn.cart.block) return;
    var eBtn = document.getElement('.f_buyBtn a');
    if (eBtn) {
      Ngn.cart.block.eProduct = document.getElement('.f_image img');
      var itemId = document.getElement('.oneItem .item').get('data-id');
      eBtn.addEvent('click', function(e) {
        e.preventDefault();
        Ngn.cart.block.addItem(Ngn.site.page.id, itemId);
     });
    }
  }).delay(100);
});