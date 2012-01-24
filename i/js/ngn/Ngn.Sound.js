Ngn.Sound = new Class({ 

  Implements: [Options, Events],

  options: {
    autostart: false,  //autostart
    streaming: true,   //streaming
    volume: 50,        //volume to start at
    pan: 0,            //pan between -100 (left) and 100 (right)
    progressInterval: 500, //milliseconds between getProgress(); calls
    positionInterval: 500,//milliseconds between getPosition(); calls
    onRegister: $empty,//fires when the sound is registered
    onLoad: $empty,    //fires when the sound is downloaded
    onPlay: $empty,    //fires when the sound begins playing
    onPause: $empty,   //fires when the sound is paused
    onStop: $empty,    //fires when the sound stops playing
    onComplete: $empty, //fires when the sound completes playing
    onProgress: $empty,//fires when download makes progress
    onPosition: $empty,//fires when position within the song changes
    onID3: $empty      //fires when ID3 tags become available
  },

  initialize: function(url, manager, options) {
    this.setOptions(options);
    this.url = url;
    this.id3 = new Hash();
    this.manager = manager || Playlist;
    this.swf = this.manager.obj.toElement();
    this.playing = false;
    this.listeners = {};
    this.filesize = null;
    this.duration = null;
    this.pausedAt = 0;
    this.position = 0;
    this.register();
  },

  start: function(position) {
    var pos = position || this.pausedAt;
    this.swf.startSound(this.url, pos, this.options.volume, this.options.pan);
    this.fireEvent('onPlay');
    this.pausedAt = 0;
    return this;
  },

  stop: function() {
    this.swf.stopSound(this.url);
    this.fireEvent('onStop');
    return this;
  },

  jumpTo: function(seconds) {
    $clear(this.listeners.position);
    this.start(seconds);
  },

  pause: function() {
    this.swf.stopSound(this.url);
    this.pausedAt = this.getPosition();
    this.fireEvent('onPause', this.pausedAt);
    this.fireEvent('onStop');
  },

  setVolume: function(volume) {
    this.obj.setVolume(this.url, volume);
    this.options.volume = volume;
    return this;
  },

  setPan: function(pan) {
    this.swf.setPan(this.url, pan);
    this.options.pan = pan;
    return this;
  },

  getVolume: function() {
    return this.options.volume;
  },

  getPan: function() {
    return this.options.pan;
  },

  getID3: function(tag) {
    return this.id3.get(tag);  
  },

  getBytesLoaded: function() {
    return this.swf.getBytesLoaded(this.url);
  }, 

  getFilesize: function() {
    return this.swf.getBytesTotal(this.url);
  },

  getPosition: function() {
    return this.swf.getPosition(this.url);
  }, 

  getDuration: function() {
    return this.swf.getDuration(this.url);
  },

  checkProgress: function() {
    if ($type(this.filesize) !== "number") { this.filesize = this.getFilesize(); }
    var loaded = this.getBytesLoaded(); 
    if ($type(loaded) === "number" && loaded !== this.listeners.lastProgress) { 
      var total = this.getFilesize();
      this.listeners.lastProgress = loaded;
      this.fireEvent('onProgress', [loaded, total]); 
    }
  },

  checkPosition: function() {
    var position = this.getPosition();
    this.duration = this.getDuration();
    if ($type(position) === "number" && position !== this.listeners.lastPosition) { 
      this.listeners.lastPosition = position;
      this.fireEvent('onPosition', [(position / 1000).round(), (this.duration / 1000).round()]);
    }
  },

  register: function() {
    this.fireEvent('onRegister');
    if (this.options.streaming === false) {
      this.swf.preloadSound(this.url);
      this.listeners.progress = this.checkProgress.periodical(this.options.progressInterval, this);
    }
    this.addEvents({'onLoad': this.onLoad, 'onStop': this.onStop, 'onPlay': this.onPlay});
  },

  onLoad: function() {
    $clear(this.listeners.progress);
    this.checkProgress();
  },

  onPlay: function() {
    if (this.options.streaming === true) {
      this.listeners.progress = this.checkProgress.periodical(this.options.progressInterval, this);
    }
    this.playing = true;
    this.listeners.position = this.checkPosition.periodical(this.options.positionInterval, this);
    this.manager.playing.push(this);
  },

  onStop: function() {
    $clear(this.listeners.position);
    if (this.pausedAt === 0) { this.fireEvent('onPosition', [0, this.duration]); }
    this.playing = false;
  }

});

Ngn.Sound.Playlist = new Class({

  Implements: [Events, Options],

  options: {
    swfLocation: '/i/js/moosound/MooSound.swf?'+Math.random()
  },

  initialize: function(options) {
    this.setOptions(options);
    window.addEvent('domready', function() { 
      this.swiffHome = new Element('div', {id: 'swiffHome'}).setStyles({position:'absolute','top':1,'left':1}).inject(document.body);
      this.obj = new Swiff(this.options.swfLocation, {width: 1, height: 1, container: this.swiffHome }); 
    }.bind(this));
    this.flashLoaded = false;
    this.loadQueue = [];
    this.sounds = new Hash();
    this.playing = [];
  },

  loadSounds: function(sounds, options) {
    if (!this.flashLoaded) {
      this.loadQueue.push([sounds,options]);
    } else {
      sounds = sounds || [];
      sounds.each(function(url) {
        this.loadSound(url, options);
      }, this);
    }
    return this;
  },

  loadSound: function(url, options) {
    if (!this.flashLoaded) { this.loadQueue.push([url, options]); }
    this.sounds.set(url, new Ngn.Sound(url, this, options));
    return this;
  },

  stopSounds: function() {
    this.playing.each( function(sound) { sound.stop(); });
    return this;
  },

  playRandom: function() {
    var randomKey = this.sounds.getKeys().getRandom();
    this.stopSounds();
    var sound = this.sounds.get(randomKey);
    sound.start(0);
    return this;
  },

  onSoundLoaded: function(url) {
    this.sounds.get(url).fireEvent('onLoad');
  },

  onSoundComplete: function(url) {
    this.sounds.get(url).fireEvent('onComplete').fireEvent('onStop');
    return this;
  },

  onFlashLoaded: function() {
    this.flashLoaded = true;
    this.loadQueue.each(function(arr) { this.loadSounds(arr[0], arr[1]); }.bind(this));
  },

  registerID3: function(url, tag, value) {
    var sound = this.getSound(url);
    sound.id3.set(tag, value);
    sound.fireEvent('onID3', [tag, value]);
  },

  getSound: function(key) {
    return this.sounds.get(key);
  }

});

var Playlist = new Ngn.Sound.Playlist();

Ngn.Sound.Player = new Class({
  
  /**
   * ePlaylist: <div id="playlist"></div>
   * songs: ["mp3/sound.mp3"]
   */
  initialize: function(ePlaylist, songs, strName, itemId, userId) {
    this.ePlaylist = ePlaylist;
    this.strName = strName;
    this.itemId = itemId;
    this.userId = userId;
    this.i = 0; 
    var options = {
      player: this,
      // onRegister: this.init.pass(this),
      onRegister: function() {
        this.options.player.init(this);
      },
      // onLoad: function() { },
      // onPause: function() { },
      onPlay: function() {
        this.options.player.el.addClass('playing');
        // this.options.Sound.startTimer();
      },
      onStop: function() {
        this.options.player.el.removeClass('playing');
        // this.options.Sound.stopTimer();
      },
      onProgress: function(loaded, total) {
        var percent = (loaded / total*100).round(2);
        //this.options.player.seekbar.get('tween').start('width', percent);
        this.options.player.seekbar.setStyle('width', percent);
        c('Загружено ' + loaded + '. Всего: ' + total);
      },
      // duration - сколько секунд загружено
      onPosition: function(position, duration) {
        var percent = (position/duration*100).round(2);
        // this.position.get('tween').start('left', percent);
        var p = this.options.player;
        //c(duration);
        c('Flash послал комманду установить позицию на ' + position + ' секунд. Всего загружено: ' + duration + ' секунд');
        p.position.setStyle('left', (-Math.round(p.position.getSize().x/2) + (p.seekbar.getSize().x / 100 * percent)) + 'px');
      },
      onID3: function(key, value) {
        if (key == 'TIT2') { this.title.set('text', value); }
      },
      onComplete: function() {
        Playlist.playRandom.delay(100, Playlist);
      }
    };
    Playlist.loadSounds(songs, options);
  },
  
  init: function(sound) {
    this.i++;
    
    
    this.el = new Element('div', {'class':'song'});
    // this.title     = new Element('h3', {'class':'title', text:this.url}).inject(this.el);
    this.controls     = new Element('div', {'class':'controls smIcons bordered'}).inject(this.el);
    this.seekbar      = new Element('div', {'class': 'seekbar'});
    this.seekbarContainer = new Element('div', {'class': 'seekbarContainer'}).inject(this.el);
    this.seekbar.inject(this.seekbarContainer);
    new Element('div', {'class': 'clear'}).inject(this.el); // clear
    this.position     = new Element('div', {'class':'position'}).inject(this.seekbar);
    this.cursor       = new Element('div', {'class':'cursor'}).inject(document.getElement('body'));
    this.seekbar.set('tween', {duration:500, unit:'%', link: 'cancel'});
    //this.position.set('tween', {duration:500, unit:'%', link: 'cancel'});
    this.playEl       = new Element('a', {'class':'sm-play',  title:'Играть', id:'play'+this.i }).inject(this.controls);
    this.stopEl       = new Element('a', {'class':'sm-stop',  title:'Стоп', id:'stop'+this.i }).inject(this.controls);
    this.pauseEl      = new Element('a', {'class':'sm-pause', title:'Пауза', id:'pause'+this.i}).inject(this.controls);
    new Element('i').inject(this.playEl);
    new Element('i').inject(this.stopEl);
    new Element('i').inject(this.pauseEl);
    this.stopEl.addEvent('click', function() { sound.stop(); }.bind(this));
    this.playEl.addEvent('click', function() { sound.start(); }.bind(this));
    this.pauseEl.addEvent('click', function() { sound.pause(); }.bind(this));
    
    (function() {
      sound.jumpTo(1400000);
    }).delay(2000);
    
    this.seekbar.addEvent('click', function(e) {
      var coords = this.seekbar.getCoordinates();
      // sound.duration - сколько милесекунд загружено
      var ms = ((e.page.x - coords.left)/coords.width)*sound.duration;
      
      // c([((e.page.x - coords.left)/coords.width), sound.duration]);
      c('Всего загружено милесекунд: ' + sound.duration);
      
      this.position.setStyles({
        'left': (e.page.x - coords.left - Math.round(this.position.getSize().x / 2)) + 'px',
        //'top': e.page.y + 'px'
      });
      c('Пытаемся установить курсор на ' + ms + ' милесекунд');
      sound.jumpTo(ms);
    }.bind(this));
    this.el.inject(this.ePlaylist);
    
    //var sbpos = this.seekbar.getPosition();
    this.position.setStyles({
      'left': (-Math.round(this.position.getSize().x / 2)) + 'px',
      //'top': sbpos.y + 'px'
    });
    //this.initCursor();
  },
  
  initCursor: function() {
    this.cursorHide = function() {
      $clear(this.timeoutId);
      this.timeoutId = (function() {
        this.seekbarContainer.removeClass('over');
        this.cursor.setStyle('visibility', 'hidden');
      }).delay(100, this);
    }.bind(this);
    this.cursorShow = function() {
      $clear(this.timeoutId);
      this.seekbarContainer.addClass('over');
      this.cursor.setStyle('visibility', 'visible');
    }.bind(this);
    this.cursor.addEvent('mouseover', function(e){
      e.preventDefault();
    });
    this.seekbarContainer.addEvent('mouseover', function() {
      this.cursorShow();
    }.bind(this));
    this.cursor.addEvent('mouseover', function() {
      this.cursorShow();
    }.bind(this));
    this.cursor.addEvent('mouseout', function() {
      this.cursorHide();
    }.bind(this));
    this.seekbarContainer.addEvent('mouseout', function() {
      this.cursorHide();
    }.bind(this));
    this.seekbarContainer.addEvent('click', function() {
      this.cursorShow();
    });
    this.seekbarContainer.addEvent('mousedown', function() {
      this.cursorShow();
    }.bind(this));
    this.seekbarContainer.addEvent('mousemove', function(e) {
      this.cursor.setStyles({
        'left': (e.page.x) + 'px',
        'top': this.seekbarContainer.getPosition().y-5
      });
    }.bind(this));
  },
  
  totalSeconds: 0,
  requestSeconds: 30,
  
  startTimer: function() {
    this.timerId = function() {
      if (this.totalSeconds % this.requestSeconds == 0) {
        new Request({
          'url': './s/sound-play-time'
        }).GET({
          'strName': this.strName,
          'itemId': this.itemId,
          'userId': this.userId,
          'sec': this.requestSeconds
        });
      }
      this.totalSeconds += 1;
    }.periodical(1000, this);
  },
  
  stopTimer: function() {
    $clear(this.timerId);
  }
  
});
