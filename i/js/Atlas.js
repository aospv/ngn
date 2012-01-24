/*
---

script: Atlas.js
 
description: Atlas (Map Interface)

docs: http://github.com/alexbeaudet/Atlas/blob/master/README.md

authors: 
- Alex Beaudet (http://www.alexbeaudet.com)

license:
- MIT-style license

requires: 
- core/1.2.4: '*'
- more/1.2.4.2: [Utilities.Assets]

provides: [Atlas]

...
*/


var Atlas = new Class({
  Implements: Options,
  options: {
    "search": null,
    "key": "ABQIAAAAhNX8CizjKtj6a91Szsqk4hTb-vLQlFZmc2N8bgWI8YDPp5FEVBQqwJsofuD8Npl2KPDlxkhI4Jtq9w",
    "type": "G_NORMAL_MAP",
    "zoom": 2,
    "center": [41, 0],
    "locations": [],
    "icon": {
      "image": "http://www.google.com/mapfiles/marker.png",
      "shadow": "http://www.google.com/mapfiles/shadow50.png",
      "iconSize": [20, 34],
      "shadowSize": [37, 34],
      "iconAnchor": [9, 34],
      "infoWindowAnchor": [9, 2]
    }
  },
  initialize: function(element, options) {
    this.setOptions(options);
    this.init(element);
    return this;
  },
  init: function(element) {
    this.element = element;
    if(this.element) {
      this.setLocations();
      this.loadAPI();
    }
  },
  loadAPI: function() {
    var api_key = this.options.key;
    new Asset.javascript("http://www.google.com/jsapi?key=" + api_key, {
      id: "gmap_api_" + $random(0, 100000),
      onload: function() {
        this.loadMap();
      }.bind(this)
    });
  },
  loadMap: function() {
    google.load("maps", "2", {"callback" : this.draw.bind(this)});
  },
  draw: function() {
    var zoom = (this.options.zoom == "TO_FIT") ? 5 : this.options.zoom;

    window.addEvent("unload", function() {
      google.maps.GUnload();
    });
    
    this.geocoder = new GClientGeocoder();

    this.map = new google.maps.Map2(this.element, options);
    this.map.setCenter(this.geocode(this.options.center), zoom, window[this.options.type]);
    var options = this.map.getDefaultUI();
    this.map.setUI(options);
    
    if(this.locations && !this.drawn) {
      this.drawMarkers();
    }
    if (this.options.search)
      this.search(this.options.search);
  },
  
  search: function(address) {
    this.geocoder.getLocations(address, function(response) {
      place = response.Placemark[0];
      point = new GLatLng(place.Point.coordinates[1],
                          place.Point.coordinates[0]);
      this.map.setCenter(point, 13);
      marker = new GMarker(point);
      this.map.addOverlay(marker);
      //marker.openInfoWindowHtml(place.address);      
    }.bind(this));
  },
  
  addToMap: function(response) {
  },  
  
  geocode: function(location) {
    var type = $type(location);
    if(type == "array") {
      return new GLatLng(location[0], location[1]);
    }
  },
  setLocations: function() {
    var type = $type(this.options.locations);
    if(type == "array") {
      this.locations = this.options.locations;
    }
    if(type == "string") {
      new Request.JSON({
        url: this.options.locations,
        noCache: true,
        onSuccess: function(data) {
          if($type(data) == "array") {
            this.locations = data;
          }
        }.bind(this)
      }).get();
    }
  },
  drawMarkers: function() {
    this.drawn = true;
    
    if(this.locations.length > 0) {
      var bounds = (this.options.zoom == "TO_FIT") ? new GLatLngBounds() : null;

      this.locations.each(function(location) {
        var icon = new GIcon();
        icon.image = this.options.icon.image;
        icon.shadow = this.options.icon.shadow;
        icon.iconSize = new GSize(this.options.icon.iconSize[0], this.options.icon.iconSize[1]);
        icon.shadowSize = new GSize(this.options.icon.shadowSize[0], this.options.icon.shadowSize[1]);
        icon.iconAnchor = new GPoint(this.options.icon.iconAnchor[0], this.options.icon.iconAnchor[1]);
        icon.infoWindowAnchor = new GPoint(this.options.icon.infoWindowAnchor[0], this.options.icon.infoWindowAnchor[1]);
        
        if(location.icon) {
          icon.image = location.icon.image;
          icon.shadow = location.icon.shadow;
          icon.iconSize = new GSize(location.icon.iconSize[0], location.icon.iconSize[1]);
          icon.shadowSize = new GSize(location.icon.shadowSize[0], location.icon.shadowSize[1]);
          icon.iconAnchor = new GPoint(location.icon.iconAnchor[0], location.icon.iconAnchor[1]);
          icon.infoWindowAnchor = new GPoint(location.icon.infoWindowAnchor[0], location.icon.infoWindowAnchor[1]);
        }
        
        var point = this.geocode([location.longitude, location.latitude]);
        
        if(bounds) {
          bounds.extend(point);
          this.map.setZoom(this.map.getBoundsZoomLevel(bounds));
          this.map.setCenter(bounds.getCenter());
        }
        
        var marker = new GMarker(point, icon);
        
        if(location.html) {
          marker.bindInfoWindowHtml(location.html);
          this.map.addOverlay(marker);
          if(location.show) { marker.openInfoWindowHtml(location.html); }
        } else {
          this.map.addOverlay(marker);
        }
      }.bind(this));
    }
  }
});