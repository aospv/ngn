// Dynamic Field Properties
Ngn.cp.Dfp = new Class({
  
  Implements: [Options],

  options: {
    form: 'ddFieldForm',
    dfp: 'dfp',
    fieldId: null,
    // Функции инициализации динамических свойств полей, где в имени 
    // init_list "list" - это dfpType 
    init_list: null,
    init_tagsFlat: null,
    init_tagsTree: null
  },
  
  initialize: function(options) {
    this.form = $(this.options.form) || this.options.form;
    this.dfp = $(this.options.dfp) || this.options.dfp;
    this.setOptions(options);
    // ----------------------
    $$('input[name=type]').each(function(el){ // Заменить на ф-ю возвращающую значения ряда радиобаттонов
      el.addEvent('change', function(){
        if (el.get('checked')) this.load(el.get('value'));
      }.bind(this));
    }.bind(this));
    return this;
  },
  
  load: function(type) {
    var type = this.form.values().type;
    new Ngn.Request.JSON({
      url: window.location.pathname + '?a=json_getDfpData',
      onSuccess: function(data) {
        if (!data) {
          this.dfp.set('html', '');
          return;
        }
        this.dfp.set('html', data.html);
        Ngn.cp.addFldClasses();
        this.initDfp(
          data.dfpType,
          data.fieldName,
          this.options.fieldId
        );
      }.bind(this)
    }).get({
      type: type,
      fieldId: this.options.fieldId
    });
  },

  initDfp: function(dfpType, fieldName, fieldId) {
    eval('this.options.init_' + dfpType + '(fieldName);');
  }
  
});