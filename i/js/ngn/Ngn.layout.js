Ngn.layout = {

  getElement: function() {
    return $('layout');
  },

  isHome: function() {
    return this.getElement().hasClass('home');
  },

  getPageId: function() {
    return this.getElement().get('data-pageId');
  }

}