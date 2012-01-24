Ngn.selectCR = function(eTable, onSelect) {
  
  var trs = eTable.getElements('tr');
  var matrix = [];
  var y = trs.length;
  var stop = false;
  
  var activate = function(el) {
    for (var row2=0; row2 <= el.retrieve('row'); row2++) {
      for (var col2=0; col2 <= el.retrieve('col'); col2++) {
        matrix[row2][col2].addClass('sel');
      }
    }
  };

  var deactivate = function() {
    for (var row2=0; row2 < y; row2++) {
      for (var col2=0; col2 < x; col2++) {
        matrix[row2][col2].removeClass('sel');
      }
    }
  }
    
  for (var row=0; row < trs.length; row++) {
    var tds = trs[row].getElements('td');
    if (!x) var x = tds.length;
    matrix[row] = [];
    for (var col=0; col < tds.length; col++) {
      var el = tds[col].getElement('a');
      el.store('row', row);
      el.store('col', col);
      el.addEvent('mouseover', function() {
        if (stop) return;
        activate(this);
      });
      el.addEvent('mouseout', function() {
        if (stop) return;
        deactivate();
      });
      el.addEvent('click', function() {
        if (stop) {
          deactivate();
          activate(this);
        }
        stop = true;
        onSelect([this.retrieve('row')+1, this.retrieve('col')+1]);
        return false;
      });
      matrix[row][col] = el;
    }
  }
};
