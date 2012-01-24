Ngn.PartialJob = new Class({
  Implements: [Options, Events],

  options: {
    // status: $('status'),
    // url: null,
    maxErrors: 5, // Максимальное количество запросов, возвращающих неправильный результат
    onError: $empty(),
    onComplete: $empty(),
    onStop: $empty(),
    requestParams: {},
    unknownTotalCount: false,
    stepDelay: 0
  },
  
  firstStepExtraParams: {},
  stopped: false,
  curStep: 0,
  curErrorCode: 0,
  stepsRemains: 1,
  lastAction: null,
  useLastActionOnNextErrors: {},
  
  initialize: function(options) {
    this.setOptions(options);
  },
  
  makeStep: function(step) {
    (function() {
      this._makeStep(step);
    }).delay(this.options.stepDelay, this);
  },
  
  _makeStep: function(step) {
    if (this.stopped) return;
    var req = new Hash({ step: step });
    if (this.options.requestParams) req.extend(this.options.requestParams);
    if (this.firstStepExtraParams) req.extend(this.firstStepExtraParams);
    new Request.JSON({
      url: this.options.url,
      onSuccess: function(data) {
        if (!data) throw new Error('Ошибка: нет данных');
        if (data.error) {
          this.fireEvent('error', data.error);
          if (!data.error.code)
            throw new Error('Code for error "'+data.error.message+'" is not defined');
          this.error(data.error.code);
          return;
        }
        if (!data.step) {
          throw new Error('step not defined in json data');
        }
        this.curStep = data.step.toInt();

        if (this.options.unknownTotalCount) {
          // Неизвестное количество шагов
          // array(
          //   'complete'
          // )
          if (data.complete) {
            this.complete();
          } else {
            this.fireEvent('step', {step: data.nextStep-1});
            if (!this.stopped) this.makeStep(data.nextStep);
          }
        } else {
          if (data.jobsTotal == 0) {
            this.complete();
            return;
          }
          for (var i in data) {
            if (i != 'jobsTotal' && i != 'stepsTotal' && i != 'jobsRemains' &&
                i != 'stepsRemains' && i != 'nextStep' && i != 'step' && i != 'text' &&
                i != 'lastJobResult' && i != 'complete') {
              this.firstStepExtraParams[i] = data[i];
            }
          }
          this.stepsRemains = data.stepsRemains - 1;
          // Нужно получить информацию о том, сколько осталось порций, записей и процентов
          // data = {
          //   'jobsTotal'
          //   'stepsTotal'
          //   'jobsRemains'
          //   'stepsRemains'
          // }
          if (data.jobsRemains > 0) {
            data.text = data.text ? '. '+data.text : '';
            this.fireEvent('step', data);
            if (!this.stopped) this.makeStep(data.nextStep);
          } else {
            this.complete();
          }
        }
        this.stepFinished(step);
      }.bind(this)
    }).get(req);
  },
  
  complete: function() {
    this.fireEvent('complete', this);
  },
  
  stepFinished: function(step) {
  },
  
  start: function() {
    this.stopped = false;
    this.makeStep(0);
  },
  
  stop: function() {
    this.stopped = true;
    this.fireEvent('stop', this);
  },
  
  repeat: function() {
    this.lastAction = this.repeat.bind(this);
    this.makeStep(this.curStep);
  },
  
  _continue: function() {
    this.lastAction = this._continue.bind(this);
    this.stopped = false;
    this.makeStep(this.curStep+1);
  },
  
  error: function(code) {
    if (this.useLastActionOnNextErrors[code] && this.lastAction) {
      this.lastAction.delay(2000, this);
      return;
    }
  }
  
});
