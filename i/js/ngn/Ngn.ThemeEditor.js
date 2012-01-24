Ngn.ThemeEditor = new Class({

  initialize: function() {
    var eChanger = new Element('div', {
      'class' : 'sizeChanger',
      'styles': {
        'position': 'absolute',
        'border' : '1px solid #FF0000',
        'width' : '300px',
        'height' : '50px'
      }
    });
    eChanger.inject($('menu'), 'before');
    
    
    var eMenu = $('menu');
    
    var marginTop = eMenu.getStyle('margin-top').toInt();
    var y1 = eChanger.getPosition().y;
    
    new Drag(eChanger, {
      snap: 0,
      modifiers: {x: '', y: 'top'},
      onDrag: function(el){
        eMenu.setStyle('margin-top', (marginTop + el.getPosition().y - y1) + 'px'); 
      },
    });  
  }
  
});
