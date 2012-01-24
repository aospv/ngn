Ngn.DatePicker = new Class({
  Extends: Picker.Date,
  
  options: {
    draggable: false,
    positionOffset: { x: 0, y: -1 }
  },
  
  constructPicker: function() {
    this.parent();
    this.picker.setStyle('z-index', 1000);
  }
  
});