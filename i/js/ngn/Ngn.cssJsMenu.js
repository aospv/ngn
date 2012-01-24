Ngn.cssJsMenu = function(el) {
  var n = 0;
  var eLastLi;
  el.getElement('ul').getChildren().each(function (eLi, i){
    var over = false;
    var eUl = eLi.getElement('ul');
    if (eUl) {
      eLi.addEvent('mouseover', function(){
        if (eLastLi && eLastLi != eLi) {
          eLastLi.getElement('ul').setStyle('display', 'none');
        }
        eLi.addClass('over');
        eUl.setStyle('display', 'block');
        over = true;
        eLastLi = eLi;
      });
      eLi.addEvent('mouseout', function(e) {
        eLi.removeClass('over');
        e.stopPropagation();
        over = false;
        (function() {
          if (!over) {
            eUl.setStyle('display', 'none');
          }
        }).delay(200);
      });
      eUl.getElements('li').each(function(el) {
        el.addEvent('mouseover', function(){
          eUl.setStyle('display', 'block');
          over = true;
        });
      });
    }
  });
}
