// singletone
Ngn.Dialog.Queue = new Class({
  Implements: [Options, Events],

  options: {
    onComplete: $empty()
  },
  
  current: 0,
  shade: null,

  /**
   * @param array Массив формата: [ [ Ngn.Dialog..., { ... } ], ... ]
   * @param options
   */
  initialize: function(queue, options) {
    if ($defined(Ngn.Dialog.queue)) new Error('You can create only one queue instance');
    Ngn.Dialog.queue = this;
    this.queue = queue;
    this.setOptions(options);
    this.openCurrent();
  },
  
  add: function(item) {
    this.queue[this.current+1] = item;
  },
  
  getNextOptions: function() {
    return $defined(this.queue[this.current+1]) ?
      this.queue[this.current+1][1] : false;
  },
  
  openCurrent: function() {
    if (this.current == 0) {
      this.shade = new Ngn.Dialog.Shade();
      this.shade.openShade();
    }
    this.queue[this.current][1] = $merge(this.getDialogOptions(), this.queue[this.current][1]);
    new this.queue[this.current][0](this.queue[this.current][1]);
  },
  
  getDialogOptions: function() {
    return {
      force: false, // тень отдельным объектом включается
      onCancelClose: function() {
        this.shade.closeShade();
      }.bind(this),
      onOkClose: function() {
        if (this.current == this.queue.length-1) {
          this.shade.closeShade();
          this.fireEvent('complete');
          return;
        }
        this.current++;
        this.openCurrent();
      }.bind(this)
    };
  }
  
});
