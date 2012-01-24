Playlist = new Playlist({'swfLocation' : '/i/js/moosound/MooSound.swf'});

Ngn.Sound = new Class({
  
  initialize: function(ePlaylist, songs) {
  var i = 0;
  var options = {
    'onRegister': function() {
      i++;
      this.el = new Element('div', {'class':'song'});
      //this.title        = new Element('h3', {'class':'title', text:this.url}).inject(this.el);
      this.controls     = new Element('div', {'class':'controls smIcons bordered'}).inject(this.el);
      this.seekbar      = new Element('div', {'class': 'seekbar'});
      this.seekbarContainer = new Element('div', {'class': 'seekbarContainer'}).inject(this.el);
      this.seekbar.inject(this.seekbarContainer);
      new Element('div', {'class': 'clear'}).inject(this.el); // clear
      this.position     = new Element('div', {'class':'position'}).inject(this.seekbar);
      this.seekbar.set('tween', {duration:this.options.progressInterval, unit:'%', link: 'cancel'});
      this.position.set('tween', {duration:this.options.positionInterval, unit:'%', link: 'cancel'});
      this.playEl       = new Element('a', {'class':'sm-play',  title:'123', id:'play'+i }).inject(this.controls);
      this.stopEl       = new Element('a', {'class':'sm-stop',  title:'?123', id:'stop'+i }).inject(this.controls);
      this.pauseEl      = new Element('a', {'class':'sm-pause', title:'?123', id:'pause'+i}).inject(this.controls);
      
      new Element('i').inject(this.playEl);
      new Element('i').inject(this.stopEl);
      new Element('i').inject(this.pauseEl);
      
      this.stopEl.addEvent('click', function() { this.stop(); }.bind(this));
      this.playEl.addEvent('click', function() { this.start(); }.bind(this));
      this.pauseEl.addEvent('click', function() { this.pause(); }.bind(this));
      this.seekbar.addEvent('click', function(e) {
        var coords = this.seekbar.getCoordinates();
        var ms = ((e.page.x - coords.left)/coords.width)*this.duration;
        this.jumpTo(ms);
      }.bind(this));
      this.el.inject(ePlaylist);
     },
     'onLoad': function() { },
     'onPause': function() { },
     'onPlay': function() { this.el.addClass('playing');    },
     'onStop': function() { this.el.removeClass('playing'); },
     'onProgress': function(loaded, total) {
       var percent = (loaded / total*100).round(2);
       this.seekbar.get('tween').start('width', percent * .99);
     },
     'onPosition': function(position,duration) {
       var percent = (position/duration*100).round(2);
       this.position.get('tween').start('left', percent);
     },
     'onID3': function(key, value) {
       if (key == "TIT2") { this.title.set('text', value); }
     },
     'onComplete': function() {
       Playlist.playRandom.delay(100, Playlist);
     }
  };
  //Note: I have some funky file serving going on in my widgets app, which means that
  //the filesize isn't readily accessible. So it bugs out a bit in the official demo.
  //You can try it at home for better luck.
  Playlist.loadSounds(songs, options);
  }
  
});
