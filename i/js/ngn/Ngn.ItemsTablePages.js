Ngn.ItemsTablePages = new Class({
  Extends: Ngn.ItemsTable,
  
  init: function() {
    this.parent();
    // onMenu
    this.eItems.getElements('a[class~=sitemap],a[class~=sitemapRed]').each(function(el, i) {
      el.addEvent('click', function(e){
        var eLoader = el.getParent();
        var eItem = el.getParent().getParent();
        var eLoading = eItem;
        eLoading.addClass('loading');
        var g = {};
        g[this.options.idParam] = eItem.get('id').split('_')[1];
        g['onMenu'] = el.get('class').match(/sitemapRed/) ? 1 : 0;
        new Request({
          url: window.location.pathname + '?a=ajax_onMenu',
          onComplete: function() {
            if (g['onMenu']) {
              eItem.removeClass('offMenu');
              el.removeClass('sitemapRed');
              el.addClass('sitemap');
            } else {
              eItem.addClass('offMenu');
              el.addClass('sitemapRed');
              el.removeClass('sitemap');
            }
            this.fireEvent('menuChangeComplete');
            eLoading.removeClass('loading');
          }.bind(this)
        }).GET(g);
        return false;
      }.bind(this));
    }.bind(this));
  }

});