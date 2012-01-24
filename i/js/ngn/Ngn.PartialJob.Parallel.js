Ngn.PartialJob.Parallel = new Class({
  
  Extends: Ngn.PartialJob,
  
  options: {
    threads: 5
  },
  
  timeouts: {},
  
  stepFinished: function(step) {
    if (step != 0) return;
    for (var i=1; i<=this.options.threads-1; i++)
      this.timeouts[step] = this.makeStep.delay(i*1000, this, step+1);
  },

  makeStep: function(step) {
    if (this.stepsRemains < 0) {
      $clear(this.timeouts[step]);
      return;
    }
    this.parent(step);
  }  
  
});