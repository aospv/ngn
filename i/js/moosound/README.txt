MooSound
========================

MooSound is a MooTools API for Flash-enabled sound management.

That may not seem very exciting on its own, but if you need to add sounds to
your page using MooTools, 
you'll probably find it useful.

Updated for the final release of MooTools 1.2.

License
------------------------

Copyright (c) 2007, 2008 Michelle Steigerwalt <http://msteigerwalt.com>
and released under LGPL 2.1 <http://creativecommons.org/licenses/LGPL/2.1/>.

Credits
------------------------

This plugin utilizes and comes packaged with the MooTools JavaScript framework.
This version of the plugin requires MooTools version 1.2.
You can build a custom download to meet your needs at
<http://www.mootools.net>.

The Robot Song is from the Interrobang Cartel
<http://interrobang.jwgh.org/songs/>.

The buttons in the demo once again come from the Sweetie Icon Pack
<http://sweetie.sublink.ca/>.  (Thanks again, Joe!)

Everything else created by Michelle Steigerwalt <http://msteigerwalt.com>.

Boring API Documentation
========================

Here's a really long, really detailed listing of every method callable
with this API (hopefully I'll find a more engaging way to display all this raw
information later).

Class Composition
------------------------

Playlist is a global Singleton with methods for manipulating the Sound objects
on a page.

The most notable methods are loadSound(s) and getSound.

Playlist.loadSounds
------------------------

Loads an array of URLs as Sound files.

### Syntax:

	Playlist.loadSounds(songsArray[, options]);

### Arguments:

1. songsArray - (*array*) An array of strings representing the locations of
sound files to include.
2. options - (*object*, optional) The options (see the options section of the
Sound class documentation below).

### Returns:

* (*Playlist*) The Playlist instance.

### Example:

    var sounds = ['song1.mp3', 'song2.mp3', 'song3.mp3'];
    
	Playlist.loadSounds(sounds, {
		onLoad: function() {
			alert("Sound loaded!");
		}
	});



Playlist.loadSound
------------------------

Exactly the same as loadSounds above, except that a string is passed as the
sound URL instead of an array of strings.

### Syntax:

	Playlist.loadSound(song[, options]);

### Arguments:

1. song - (*string*) A string representing the location of sound file to
register.
2. options - (*object*, optional) The options (see the options section of the
Sound class documentation below).

### Returns:

* (*Playlist*) The Playlist instance.

### Example:

	Playlist.loadSound('mySound.mp3', { 
		onLoad: function() { 
	   		alert("Sound loaded!");
		}
	});



Playlist.getSound
------------------------

Returns a Sound object based on its key (key defaults to URL).

### Syntax:

	Playlist.getSound(key);

### Arguments:

1. key - (*string*) The key (URL) of the Sound object.
 
### Returns:

* (*Sound*) The Sound instance or null.

### Example:

	var mySound = Playlist.getSound('mySound.mp3').start();



Playlist.stopSounds
------------------------

Stops all playing sounds immediately.

### Syntax:

	Playlist.stopSounds();

### Returns:

* (*Playlist*) The Playlist instance.

### Example:

	Playlist.stopSounds();
	//Silence.



Playlist.playRandom
------------------------

Plays a random Sound.

### Syntax:

	Playlist.playRandom();

### Returns:

* (*Playlist*) The Playlist instance.

### Example:

    //Silence.
	Playlist.playRandom();
	//Music, yay! \o/



Sound Methods
------------------------

NOTE: The Sound.initialize method should be treated as a private method.

**Instantiating Sound objects should only occur through the Playlist Class.**

### Options:

* autostart - (*boolean*, defaults to false) If autostart is set to true, the
song will begin playing as soon as it's ready.
* streaming - (*boolean*, defaults to true) If streaming is set to true, the
file will be streamed, rather than downloaded and then played.
* volume - (*int*, defaults to 50) A value from 0-100, representing the volume
of the Sound.
* pan - (*int*, defaults to 0) A value from -50 (left) to 50 (right),
representing the balance of the Sound.
* progressInterval - (*int*, defaults to 500) The length of time (milliseconds)
between calls to the Flash instance in order to check the progress of the Sound
file download.
* positionInterval - (*int*, defaults to 500) The length of time (milliseconds)
between calls to the Flash instance in order to check the current time position
of the Sound file.

### Sound Events:

#### onRegister 

Fires when the sound is registered with the SWF file.

#### onLoad

Fires when the sound has completed its download.

#### onPlay

Fires when the sound begins playing.

#### onPause

Fires when the sound is paused.

#### onStop

Fires when the sound stops playing.

#### onFinish 

Fires when the sound completes playing.

#### onProgress 

Fires when the sound file's download has made progress.

1. bytesLoaded - (*int*) The number of bytes currently loaded of the sound
file.
2. bytesTotal  - (*int*) The number of bytes total of the sound file.

#### onPosition 

Fires when the sound's position in its playback timeline changes.

1. currentPosition - (*int*) The current position (in seconds) of the sound
file.
2. totalDuration   - (*int*) The total duration (in seconds) of the sound file.

#### onID3

Fires when a new ID3 tag is ready.

1. tag   - (*string*) The tag name of the ID3 information.
2. value - (*string*) The value of the ID3 information.



Sound.start
------------------------

### Syntax:

	mySound.start([seconds]);

### Arguments:

1. seconds - (*int*, optional) The position offset (in seconds) to begin
playback from.

### Returns:

* (*Sound*) The current Sound object.

### Example:

    //Starts sound1.mp3 at 10 seconds:
	mySounds.start(10);

Sound.stop
------------------------

Immediately stops the playback of the target Sound object.  The Sound's
position will be reset to 0.

### Syntax:

	mySound.start();

### Returns:

* (*Sound*) The current Sound object.

### Example:

	mySounds.stop();

Sound.jumpTo
------------------------

### Syntax:

	mySound.jumpTo(seconds);

### Arguments:

1. seconds - (*int*) The position offset (in seconds) to transfer current
position to.

### Returns:

* (*Sound*) The current Sound object.

### Example:

    //Immediately jumps to one minute into the Sound.
	mySound.jumpTo(60);



Sound.pause
------------------------

Immediately halts playback of the Sound and saves its current position.

### Syntax:

	mySound.pause();

### Returns:

* (*Sound*) The current Sound object.

### Example:

    //Immediately jumps to one minute into the Sound:
    mySound.start(25);
    // [... time passes ... ]
	mySound.pause();
	//Resumes playback from the paused position:
	mySound.start();


Sound.setVolume
------------------------

Immediately sets the volume of the target Sound to the new value.

### Syntax:

    mySound.setVolume(percent);

### Arguments:

1. percent - (*int*) The volume percentage to set.

### Returns:

* (*Sound*) The current Sound object.

### Example:

    //Sets the maximum volume for a sound:
    mySound.setVolume(100);
    //Mutes the sound:
    mySound.setVolume(0);



Sound.getVolume
------------------------

Returns the current volume of the Sound.

### Syntax:

    mySound.getVolume();

### Returns:

* (*int*) The current volume of the Sound, from 0 to 100.



Sound.setPan
------------------------

Immediately sets the balance of the target Sound to the new value.

### Syntax:

    mySound.setPan(pan);

### Arguments:

1. pan - (*int*) The balance to set, between -50 (left) to 50 (right).

### Returns:

* (*Sound*) The current Sound object.

### Example:

    //Sets the balance of the Sound to center:
    mySound.setPan(0);
    //Sets the balance of the Sound 100% left:
    mySound.setPan(-50);



Sound.getPan
------------------------

Returns the current balance of the Sound.

### Syntax:

    mySound.getPan();

### Returns:

* (*int*) The current balance of the Sound, from -50 (left) to 50 (right).


Sound.getID3
------------------------

Returns the value of the passed ID3 tag, if found.

### Syntax:

    mySound.getID3(tag);

### Arguments:

1. tag - (*string*) The name of the tag to retrieve the value of.
    
### Returns:

* (*string*) The value of the requested tag (or nothing).

### Example:

    var title = mySound.getID3('TIT2');
    if (title) alert('My title is: '+ title);

### Note:

ID3 tags must be registered before they can be accessed.  See the onID3 event
for more information.


Sound.getBytesLoaded
------------------------

Returns the number of bytes loaded of the sound file.

### Syntax:

    mySound.getBytesLoaded();

### Returns:

* (*int*) The number of bytes loaded.

### Note:

It shouldn't be necessary to call this most cases, as the onProgress event is
fired every time this value changes.



Sound.getFilesize
------------------------

Returns the total size (in bytes) of the sound file.

### Syntax:

    mySound.getFilesize();

### Returns:

* (*int*) The total filesize of the sound file (in bytes).

### Note:

It shouldn't be necessary to call this most cases, as the onProgress event is
fired every time this value changes.


Sound.getPosition
------------------------

Returns the current position (in seconds) of the sound file.

### Syntax:

    mySound.getPosition();

### Returns:

* (*int*) The number of seconds the current Sound is playing at.

### Note:

It shouldn't be necessary to call this most cases, as the onPosition event is
fired every time this value changes.

Sound.getDuration
------------------------

Returns the duration (in seconds) of the sound file.

### Syntax:

    mySound.getDuration();

### Returns:

* (*int*) The duration of the sound file in seconds.

### Note:

It shouldn't be necessary to call this most cases, as the onPosition event is
fired every time this value changes.

