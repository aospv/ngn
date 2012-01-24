Ngn.msgs.EditLayout = new Class({
  
  initialize: function(id, elEdit, objMsg) {
    if (!elEdit) return;
    this.objMsg = objMsg;
    elEdit.getElements('a').each(function(btn, i) {
      btn.addEvent('click', function(e) {
        new Event(e).stop();
        var method = 'this.objMsg.action_' + btn.getProperty('class').replace('sm-', '');
        if ($defined(eval(method))) {
          eval(method+'(id)');
        }
      }.bind(this));
    }.bind(this));
  }
  
});
