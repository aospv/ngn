Ngn.PartialJob.Dialog = new Class({
  Extends: Ngn.Dialog.Loader.Advanced,
  
  options: {
    width: 500,
    stepTitle: 'Осталось {jobsRemains} из {jobsTotal}{text}',
    stepUnknownTitle: 'Обработано {step}',
    completeTitle: 'Готово',
    closeOnComplete: true,
    pj: Ngn.PartialJob,
    pjOptions: {},
    stopText: 'Остановить',
    startText: 'Начать',
    continueText: 'Продолжить',
    repeatText: 'Повторить'
    //repeatCheckbox: true
  },
  
  init: function() {
    this.parent();
    //if (this.options.repeatCheckbox)
    //  this.eCheckboxContainer = '<div class="checkbox"><input type="checkbox" id="checkbox1" /><label for="checkbox1">Повторять то же самое действие для всех последующих случаев</label></div>'.toDOM()[0].inject(this.message.getElement('.message-text'));
    this.pj = new this.options.pj(this.options.pjOptions);
    this.pj.addEvents({
      step: function(data) {
        if (this.pj.options.unknownTotalCount)
          this.setTitle(Ngn.tpl(this.options.stepUnknownTitle, data));
        else
          this.setTitle(Ngn.tpl(this.options.stepTitle, data));
      }.bind(this),
      complete: function() {
        this.setTitle(this.options.completeTitle);
        if (this.options.closeOnComplete) this.close();
        else this.stopPj();
      }.bind(this)
    });
    this.addEvent('close', function() {
      this.pj.stop();
    }.bind(this));
    
    //if (this.options.repeatCheckbox)
    //  this.eCheckbox = this.eCheckboxContainer.getElement('#checkbox1');
    this.createButton(
      'stop',
      this.options.id,
      this.options.stopText,
      'stop',
      this.stopPj.bind(this),
      true
    ).inject(this.footer.firstChild, 'bottom');
    this.createButton(
      'start',
      this.options.id,
      this.options.startText,
      'play',
      this.startPj.bind(this),
      true
    ).inject(this.footer.firstChild, 'bottom');
    this.createButton(
      'continue',
      this.options.id,
      this.options.continueText,
      'play',
      this.continuePj.bind(this),
      true
    ).inject(this.footer.firstChild, 'bottom');
    this.createButton(
      'repeat',
      this.options.id,
      this.options.repeatText,
      'repeat',
      this.repeatPj.bind(this),
      true
    ).inject(this.footer.firstChild, 'bottom');
    this.toggle('repeat', false);
    this.toggle('continue', false);
    this.toggle('stop', false);
  },
  
  startPj: function() {
    this.toggle('start', false);
    this._startPj();
    this.pj.start();
  },
  
  continuePj: function() {
    this.toggle('continue', false);
    this._startPj();
    this.pj._continue();
  },
  
  _startPj: function() {
    this.toggle('repeat', false);
    this.toggle('continue', false);
    this.toggle('stop', true);
    // this.eCheckboxContainer.setStyle('display', 'none');
    this.start();
  },
  
  stopPj: function() {
    this.toggle('continue', true);
    this.toggle('stop', false);
    this.pj.stop();
    this.stop();
  },
  
  pausePj: function() {
    this.toggle('continue', true);
    this.toggle('stop', false);
    this.pj.stop();
    this.stop();
  },
  
  repeatPj: function() {}
  
});


/*
start: function() {
  this.btnRepeat.setStyle('display', 'none');
  this.eCheckboxContainer.setStyle('display', 'none');
  this.parent();
},

*/