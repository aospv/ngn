window.addEvent('domready', function() {
  var btnShowHiddenPrices = $('btnShowHiddenPrices');

  document.getElements('.f_price2').each(function(el) {
    el.setStyle('cursor', 'default');
    el.set('title', 'Оптовая цена');
  });
  new Tips('.f_price2');
  
  if (btnShowHiddenPrices) btnShowHiddenPrices.addEvent('click', function(e) {
    e.preventDefault();
    new Ngn.Dialog.Confirm({
      message: 'Для того, что бы узнать оптовые цены вам необходимо зарегистрироваться',
      onOkClose: function() {
        new Ngn.Dialog.Auth({
          onActivation: function() {
            new Ngn.Dialog.Msg({
              message: 'Вы отправили заявку на регистрацию. Скоро с вами свяжется менеджер'
            });
          }
        });
      }
    });
  });
});