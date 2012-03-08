Ngn.videoItems = function() {
  $$('.f_video a.thumb.popup').each(function(el) {
    el.addEvent('click', function(e) {
      e.preventDefault();
      new Ngn.Dialog.Video(JSON.decode(this.getElement('.data').get('html')));
    })
  });
}
