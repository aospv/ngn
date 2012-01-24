Ngn.Autocompleters = {
    
  init: function() {
    if (this.ac) return; // Если уже инициализированы, ничего не делаем
    this.ac = {};
    document.getElements('input[id^=ac-]').each(function(eInput) {
      var ac = new Ngn.Autocompleter(eInput);
      this.ac[ac.id] = ac;
    }.bind(this));
  }
  
}