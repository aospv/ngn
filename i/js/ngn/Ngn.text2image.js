Ngn.text2image = function(selector, font, size) {
  $(selector).each(function(el){
    var html = el.get('html');
    el.set('html', '');
    var el2 = new Element('span', {'html': html}).inject(el);
    el2.setStyle('display', 'none');
    var newImg = new Image();
    newImg.src = imgSrc;
    var height = newImg.height;
    var width = newImg.width;
  });
}