Ngn.FullScreenGallery = new Class( {
  
  hide: function() {
    this.gal.dispose();
    this.dialog.toggleShade(false);
    document.body.removeClass('bodyNoScroll');
  },

  show: function() {
    document.body.addClass('bodyNoScroll');
    this.dialog = new Ngn.Dialog({
      autoShow: false
    }).toggleShade(true);
    this.gal = new Element('div', {
      id :'gal',
      'styles': {
        'z-index': '300',
        'position': 'absolute',
        'width': '100%',
        'height': '100%',
        'top': 0,
        'left': 0
      }
    });
    this.gal.inject(document.body, 'bottom');
    var imagesData = new Array();
    $$('.ddItems .item').each( function(el, i) {
      var smImage = el.getElement('a.thumb img').get('src');
      var mdImage = smImage.replace('sm_', 'md_');
      var lgImage = smImage.replace('sm_', '');
      imagesData[i] = {
        image: mdImage,
        number: i,
        transition: 'fade', // gallery.options.defaultTransition,
        title: el.getElement('h2 a').get('text'),
        description: '',
        link: lgImage,
        linkTitle: false,
        linkTarget: false,
        thumbnail: smImage
      };
    });
    // console.debug(imagesData);
    var gal = new gallery(this.gal, {
      manualData :imagesData
    });
  }

});