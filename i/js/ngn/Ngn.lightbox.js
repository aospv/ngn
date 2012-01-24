// global wrapper to global milkbox object
Ngn.lightbox = {};
Ngn.lightbox.add = function(els, name) {
  if (!els.length) return;
  if (!name) name = 'g' + Ngn.getRandomInt(0, 10000);
  var files = [];
  els.each(function(el, i) {
    el.addEvent('click', function(e) {
      e.preventDefault();
      milkbox.open(name, i);
    });
    eImg = el.getElement('img');
    files.push({
      href: el.get('href'),
      title: eImg ? eImg.get('title') : ''
    });
  });
  if (!files.length) return;
  milkbox.addGalleries([{
    name: name,
    files: files
  }]);
};
