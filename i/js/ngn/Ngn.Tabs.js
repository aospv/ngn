Ngn.Tabs = new Class({
  Implements: [Events, Options],

  options: {
    show: 0,
    selector: '.tab-tab',
    classWrapper: 'tab-wrapper',
    classMenu: 'tab-menu',
    classContainer: 'tab-container',
    onSelect: function(toggle, container, index) {
    },
    onDeselect: function(toggle, container, index) {
      toggle.removeClass('tab-selected');
      container.setStyle('display', 'none');
    },
    onRequest: function(toggle, container, index) {
      container.addClass('tab-ajax-loading');
    },
    onComplete: function(toggle, container, index) {
      container.removeClass('tab-ajax-loading');
    },
    onFailure: function(toggle, container, index) {
      container.removeClass('tab-ajax-loading');
    },
    onAdded: Class.empty,
    getContent: null,
    ajaxOptions: {},
    cache: true,
    stopClickEvent: false
  },

  /**
   * Constructor
   *
   * @param {Element} The parent Element that holds the tab elements
   * @param {Object} Options
   */
  initialize: function(element, options) {
    this.element = $(element);
    this.setOptions(options);
    this.selected = null;
    this.build();
  },

  build: function() {
    this.tabs = [];
    this.eMenu = new Element('ul', {'class': this.options.classMenu});
    this.wrapper = new Element('div', {'class': this.options.classWrapper});
    this.element.getElements(this.options.selector).each(function(el) {
      var n = el.get('data-name') || el.get('title') || el.get('html');
      var content = el.get('href') || (this.options.getContent ? this.options.getContent.call(this, el) : el.getNext());
      this.addTab(el.innerHTML, el.get('title') || el.get('html'), el.get('data-id') || el.get('id'), content);
    }, this);
    this.element.empty().adopt(this.eMenu, this.wrapper);
    if (this.tabs.length) this.select(this.options.show);
  },

  /**
   * Add a new tab at the end of the tab menu
   *
   * @param {String} inner Text
   * @param {String} Title
   * @param {Element|String} Content Element or URL for Ajax
   */
  addTab: function(text, title, name, content) {
    var grab = $(content);
    var container = (grab || new Element('div'))
      .setStyle('display', 'none')
      .addClass(this.options.classContainer)
      .inject(this.wrapper);
    var pos = this.tabs.length;
    var evt = (this.options.hover) ? 'mouseenter' : 'click';
    var tab = {
      name: name,
      container: container,
      toggle: new Element('li').grab(new Element('a', {
        href: window.location.pathname + '#' + name,
        title: title
      }).grab(
        new Element('span', {html: text})
      )).addEvent(evt, this.onClick.bindWithEvent(this, [pos])).inject(this.eMenu)
    };
    if (!grab && $type(content) == 'string') tab.url = content;
    this.tabs.push(tab);
    return this.fireEvent('onAdded', [tab.toggle, tab.container, pos]);
  },

  onClick: function(evt, index) {
    this.select(index);
    if (this.options.stopClickEvent) return false;
  },

  /**
   * Select the tab via tab-index
   *
   * @param {Number} Tab-index
   */
  select: function(index) {
    if (this.selected === index || !this.tabs[index]) return this;
    if (this.ajax) this.ajax.cancel().removeEvents();
    var tab = this.tabs[index];
    var params = [tab.toggle, tab.container, index];
    if (this.selected !== null) {
      var current = this.tabs[this.selected];
      if (this.ajax && this.ajax.running) this.ajax.cancel();
      params.extend([current.toggle, current.container, this.selected]);
      this.fireEvent('onDeselect', [current.toggle, current.container, this.selected]);
    }
    
    tab.toggle.addClass('tab-selected');
    tab.container.setStyle('display', '');
    
    this.fireEvent('onSelect', params);
    if (tab.url && (!tab.loaded || !this.options.cache)) {
      this.ajax = this.ajax || new Request.HTML();
      this.ajax.setOptions({
        url: tab.url,
        method: 'get',
        update: tab.container,
        onFailure: this.fireEvent.pass(['onFailure', params], this),
        onComplete: function(resp) {
          tab.loaded = true;
          this.fireEvent('onComplete', params);
        }.bind(this)
      }).setOptions(this.options.ajaxOptions);
      this.ajax.send();
      this.fireEvent('onRequest', params);
    }
    this.selected = index;
    return this;
  },
  
  selectByName: function(name) {
    for (var i=0; i<this.tabs.length; i++)
      if (this.tabs[i].name == name)
        this.select(i);
  }
  
});