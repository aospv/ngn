Ngn.rating = function(rate, addevents, options) {
  
  var options = $merge({
    isMinus: true,
    maxStars: 5, 
    clickEvent: $empty(),
    onComplete: $empty(),
    starTitle: 'Отдать %n',
    starMinusTitle: 'Забрать %n'
  }, options);
  
  var ratinger = new Element('div', {
  });

  if (options.isMinus) {
    var rIndex = -options.maxStars;
    var maxStars = options.maxStars * 2;
  } else {
    var rIndex = 1;
    var maxStars = options.maxStars;
  }
  for (var ii = 1; ii <= maxStars; ++ii) {
    new Element('div', {
      'class': ((addevents) ? 
        'cur rating' + options.targetObject.id : 'rating' + options.targetObject.id) +
        ((options.isMinus && ii <= options.maxStars) ? ' minus' : ''),
      'data-index': ii,
      'data-rindex': rIndex,
      'title': (options.isMinus && rIndex < 0) ?
        options.starMinusTitle.replace('%n', -rIndex) :
        options.starTitle.replace('%n', rIndex),
      id: 'v' + ii
    }).inject(ratinger);
    rIndex == -1 ? rIndex+=2 : rIndex++;
  }
  ratinger.inject(options.targetObject.empty());
  $$('.rating' + options.targetObject.id).each(function(el, i) {
    if (!addevents)
      if (i < options.targetObject.get('data-value'))
        el.addClass('checked');
    el.addClass('bnw');
    if (addevents)
      el.addEvents({
        mouseenter: function() {
          var curIndex = el.get('data-index');
          $$('.rating'+options.targetObject.id).each(function(plains, i) {
            if (!options.isMinus) {
              (i < curIndex) ? plains.addClass('over') : plains.removeClass('over');
            } else {
              if (i > options.maxStars-1) {
                (i < curIndex) ?
                  plains.addClass('over') : plains.removeClass('over');
              } else {
                (i >= curIndex - 1) ?
                  plains.addClass('over') : plains.removeClass('over');
              }
            }
          });
        },
        mouseleave: function() {
          $$('.rating'+options.targetObject.id).each(function(plains, i) {
            plains.removeClass('over');
          });
        },
        click: function() {
          options.targetObject.set('data-value', el.get('data-rindex'));
          if ($type(options.clickEvent) == 'function') {
            var n = options.targetObject.get('data-value');
            options.clickEvent.run({
              id: options.targetObject.get('id'), 
              n: n,
              onComplete: options.onComplete.run(n)
            });
          }
          Ngn.rating(el.get('data-index'), false, options);
        }
      });
  });
}
   