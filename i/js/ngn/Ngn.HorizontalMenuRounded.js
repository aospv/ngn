Ngn.HorizontalMenuRounded = new Class({
  Extends: Ngn.HorizontalMenu,
  
  backgrounds: {},
  
  options: {
    enlargeSubmenuWidth: true,
    roundBorderWidth: 0,
    radius: 0
  },
  
  init: function() {
    // this.eUl.setStyle('background-image', );
    this.eUl.getElements('ul').each(function(eSubUl){
      var lis = eSubUl.getElements('li');
      for (var i=0; i < lis.length; i++) {
        if (i == 0) lis[i].addClass('first');
        else if (i == lis.length-1) lis[i].addClass('last');
      }
    });
  },
  
  show: function(eLi) {
    var id, eUl, ulSize, sss, liSize, ulSize, equalWidth, tlWidth;
    if (!this.parent(eLi)) return;
    id = eLi.get('id');
    if (!id) throw new Error('id not defined in <li>');
    eUl = eLi.getElement('ul');
    /*
    eUl = eLi.retrieve('ul');
    if (!eUl) {
      eUl = eLi.getElement('ul');
      eLi.store('ul', eUl);
      eUl.fx = new Fx.Tween(eUl);
    }
    eUl.fx.set('opacity', 0);
    */
    var ulPos = eUl.getPosition();
    equalWidth = false;
    if (!this.backgrounds[id]) {
      eUl.setStyle('padding-bottom', Math.round(this.options.radius/3)+'px');
      ulSize = eUl.getSize();
      // Если элемент фона ещё не был создан
      sss = '<div class="submenuBgP tl"></div>' + 
      '<div class="submenuBgP tr"></div>' + 
      '<div class="submenuBgP bl"></div>' + 
      '<div class="submenuBgP br"></div>';
      liSize = eLi.getSize();
      if (ulSize.x > liSize.x) {
        if (ulSize.x % 2 != 0) {
          ulSize.x++;
          eUl.setStyle('width', ulSize.x + 'px');
        }
      } else {
        equalWidth = true;
        // Если ширина сабменю меньше ширины ячейки 1-го уровня
        if (this.options.enlargeSubmenuWidth) {
          if (liSize.x % 2 != 0) {
            liSize.x++;
            eLi.setStyle('width', liSize.x + 'px');
          }
          // Перед созданием элемента фона, изменяем размеры UL
          eUl.setStyle('width', liSize.x + 'px');
          var ulSize = eUl.getSize();
        }
      }
      if (ulSize.y % 2 != 0) ulSize.y++;
      this.backgrounds[id] =
        ('<div class="submenuBg'+(equalWidth ? ' equalWidth' : '')+'">'+sss+'</div>').
        toDOM()[0].inject(window.document.getElement('body'));
      //this.backgrounds[id].fx = new Fx.Tween(this.backgrounds[id]);
      //this.backgrounds[id].fx.set('opacity', 0);
      this.backgrounds[id].setStyles({
        'width': ulSize.x + 'px',
        'height': ulSize.y + 'px'
      });
      if (ulSize.x > liSize.x) {
        tlWidth = (liSize.x-this.options.roundBorderWidth);
        this.backgrounds[id].getElement('.tl').setStyle('width', tlWidth+'px');
        this.backgrounds[id].getElement('.tr').setStyle('width', (ulSize.x-tlWidth)+'px');
      }
      if (eLi.hasClass('openLeft')) this.backgrounds[id].addClass('openLeft');
      if (eLi.hasClass('openRight')) this.backgrounds[id].addClass('openRight');
    }
    this.backgrounds[id].setStyle('display', 'block');
    this.backgrounds[id].setStyles({
      'left': ulPos.x + (Browser.Engine.gecko ? 1 : 0),
      'top': ulPos.y
    });
  },

  hide: function(eLi) {
    this.parent(eLi);
    var id = eLi.get('id');
    if (!this.backgrounds[id]) return;
    this.backgrounds[id].setStyle('display', 'none');
  }

});