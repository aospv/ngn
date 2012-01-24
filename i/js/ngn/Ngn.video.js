Ngn.player;

function playerReady(obj) {
  Ngn.player = document.getElementById(obj['id']);
  //Ngn.player.sendEvent('PLAY', 'true');
};

Ngn.video = function(options, flashvars) {
  
  var options = $merge({
    'player': 'i/swf/mp/player.swf',
    'container': null,
    'wmode': 'opaque',
    'width': 320,
    'height': 240,
    'bgcolor': '#000000'
  }, options);
  
  var flashvars = $merge({
    //'mute': true,
    //'repeat': 'always',
    //'file': null,
    //'image': null,
    'autostart': false,
    'date': '',
    'title': '',
    'author': 'Автор',
    'skin': 'beelden',
    'fullscreen': true,
    //'backcolor': 'black'
  }, flashvars);
  
  flashvars.skin = 'i/swf/mp/skin/'+flashvars.skin+'.zip';
  
  
  var s = new SWFObject(options.player, 'asd', options.width, options.height, 9);
  s.addParam('allowfullscreen', 'true');
  s.addParam('wmode', options.wmode);
  s.addParam('allowscriptaccess', 'always');
  s.addParam('flashvars', http_build_query(flashvars));
  s.addParam('bgcolor', options.bgcolor);
  s.addParam('allowfullscreen','true');
  
  //c(options.container);
  if (options.container) {
    s.write(options.container);
  } else {
    var eContainer = new Element('div', {
      //'html': 'Здесь должен появиться плеер'
    });
    s.write(eContainer);
    return eContainer;
  }
}