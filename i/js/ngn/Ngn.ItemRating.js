Ngn.ItemRating = new Class({
  
  Implements: [Options],

  options: {
    rateTitle: 'Голосовать',
    ratingTitle: 'Рейтинг',
    votersWindowTitle: 'Голосовавшие пользователи',
    showRatingBeforeRate: true,
    isMinus: true,
    maxStars: 5,
    allowVotingLog: false,
    strName: null
  },
  
  initialize: function(el, options) {
    if (!el) return;
    this.setOptions(options);
    this.element = document.id(el);
    this.itemId = this.element.get('id').replace('ddRating', '');
    if (this.options.maxStars && this.element.hasClass('canRate')) {
      var N = this.element.get('text');
      this.element.empty();
      var eB = new Element('b', {
        'html': 
          (this.options.showRatingBeforeRate ?
            (this.options.ratingTitle + ': ' + N + ' &nbsp;&nbsp; ') : '') +
          this.options.rateTitle + ':',
        'class': 'title'
      }).inject(this.element);
      var starsContainer = new Element('div', {
        'id': this.itemId
      }).inject(eB, 'after');
      Ngn.rating(0, true, {
        targetObject: starsContainer,
        isMinus: this.options.isMinus,
        maxStars: this.options.maxStars,
        clickEvent: function(opt) {
          new Request({
            url: Ngn.getPath(1) + '?a=ajax_rate&itemId=' + opt.id + '&n=' + opt.n,
            onComplete: opt.onComplete
          }).send();
        },
        onComplete: function(n) {
          this.switchRating(N.toInt() + n.toInt());              
        }.bind(this)
      });
    } else {
      this.switchRating(this.element.get('text').toInt());
    }
  },
  
  switchRating: function(n) {
    this.element.empty();
    if (this.options.allowVotingLog) {
      var eN = new Element('a', {
        'html': n,
        'href': '#',
        'class': 'title',
        'events': {
          'click': function(e) {
            new Ngn.Dialog({
              title: this.options.votersWindowTitle,
              url: './c/rating/ajax_voters/' + this.options.strName + '/' + this.itemId,
              cancel: false
            }).show();
            return false;
          }.bind(this)
        }
      }).inject(this.element);
    } else {
      var eN = new Element('span', {
        'html': n,
        'class': 'title'
      }).inject(this.element);
    }
    new Element('b', {
      'html': this.options.ratingTitle + ':',
      'class': 'title'
    }).inject(eN, 'before');
  }
  
});