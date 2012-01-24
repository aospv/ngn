Ngn.cp.ddFieldType = {};
Ngn.cp.ddFieldType.types = {};

Ngn.cp.ddFieldType.Properties = new Class({
  
  initialize: function(eForm, name) {
    var eName = eForm.getElement('#namei');
    var copyValue = function() {
      eForm.getElement('#titlei').set('value', eName.get('value'));
    };
    var init = function(type) {
      if (Ngn.cp.ddFieldType.types[type].virtual) {
        //copyValue();
        //eForm.getElement('.row_title').setStyle('display', 'none');
        eForm.getElement('.row_required').setStyle('display', 'none');
        //eName.addEvent('change', copyValue);
      } else {
        //eForm.getElement('.row_title').setStyle('display', 'block');
        eForm.getElement('.row_required').setStyle('display', 'block');
        //eName.removeEvent('change', copyValue);
      }
      if (Ngn.cp.ddFieldType.types[type].system) {
        eForm.getElement('.row_defaultDisallow').setStyle('display', 'none');
        eForm.getElement('.row_system').setStyle('display', 'none');
      } else {
        eForm.getElement('.row_defaultDisallow').setStyle('display', 'block');
        eForm.getElement('.row_system').setStyle('display', 'block');
      }
      if (Ngn.cp.ddFieldType.types[type].notList) {
        eForm.getElement('.row_notList').setStyle('display', 'none');
      } else {
        eForm.getElement('.row_notList').setStyle('display', 'block');
      }
    }
    Ngn.frm.addEvent('change', name, function(type) {
      init(type);
    });
    var selType = Ngn.frm.getValueByName(name);
    if (Ngn.cp.ddFieldType.types[selType].virtual) init(selType);
  }
  
});