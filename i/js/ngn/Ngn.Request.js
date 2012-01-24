Ngn.Request = new Class({
  Extends: Request,
  
  success: function(text, xml) {
    if (text.contains('Error: '))
      new Ngn.Dialog.Error({
        width: 600,
        message: 'Ошибка запроса: '+this.options.url
      });
    this.parent(text, xml);
  }
  
});


Ngn.Request.JSON = new Class({
  Extends: Request.JSON,
  
  success: function(text){
    this.response.json = JSON.decode(text, this.options.secure);
    if (this.response.json === null) {
      this.onSuccess({});
      return;
    }
    if (this.response.json.error) {
      new Ngn.Dialog.Error({ error: this.response.json.error });
      return;
    }
    this.onSuccess(this.response.json, text);
  },
  
  failure: function(xhr) {
    new Ngn.Dialog.Error({message: this.xhr.responseText + '<hr/>URL: ' + this.options.url});
    this.parent();
  }
  
});
