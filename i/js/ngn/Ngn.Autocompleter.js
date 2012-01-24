Ngn.Autocompleter = new Class({
  
  Extends: Autocompleter.Request.JSON,
  
  options: {
    caption: 'Нашмите клавишу "вниз" для выбора вариантов',
    postVar: 'mask',
    minLength: 1,
    //onFocus: function(el) {
      //el.set('value', '');
    //},
    //onBlur: function(el) {
      //el.set('value', this.selectedValue);
    //},
    onSelection: function(el, d) {
      this.selectedValue = this.element.value;
      var eInputValue = $('fld-' + this.id);
      // Если HIDDEN поле существует
      if (eInputValue) eInputValue.set('value', d.inputKey);
      //else this.eInputHidden.set('value', d.inputKey);
      this.element.setStyle('background', 'none');              
    },
    onRequest: function(){
      this.eSpan2.addClass('loader');
    },
    onComplete: function(){
      this.eSpan2.removeClass('loader');
    }
  },  
  
  initialize: function(eInput, options) {
    this.selectedValue = '';
    this.id = eInput.get('id').replace('ac-', '');
    var actionKey = eInput.get('title');
    if (!actionKey) actionKey = this.id;
    else eInput.set('title', '');
    
    this.parent(
      eInput,
      window.location.pathname + '?action=json_' + actionKey + 'Autocomplete',
      options
    );
    
    this.eSpan = new Element('span', {'class': 'ac'});
    this.eSpan2 = new Element('span', {'class': 'ac2'});
    this.eSpan.inject(this.element.getParent());
    this.eSpan2.inject(this.eSpan);
    this.element.inject(this.eSpan2);
    this.element.addClass('ac');
    this.eSpan.set('title', this.options.caption);
    this.eSpan.addClass('tooltip');
    this.element.inject(new Element('a', {'class': 'ac'}), 'after');
  },
  
  prefetch: function() {
    if (this.element.value && this.element.value == this.selectedValue) return;
    this.parent();
    //this.element.blur();
  }
  
});
