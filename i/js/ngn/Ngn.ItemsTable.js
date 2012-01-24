Ngn.ItemsTable = new Class({
  
  Extends: Ngn.Items,
  
  options: {
    itemsElementId: 'itemsTable',
    itemElementSelector: 'tbody tr',
    isSorting: true,
    handle: '.dragBox',
    onMoveComplete: $empty 
  },
  
  init: function() {
    this.parent();
    // ----------------------------
    this.eItemsTableBody = this.eItems.getElement('tbody');
    Ngn.fixEmptyTds(this.eItemsTableBody);
    ///////////////////// Sorting /////////////////////
    if (this.options.isSorting) {
      var sortablesOptions = {};
      if (this.options.handle) {
        sortablesOptions.handle = this.options.handle;
        // =============
        var dragBoxes = $$(this.options.handle);
        dragBoxes.each(function(el) {
          el.addEvent('mouseover', function() {
            el.addClass('over');
          });
          el.addEvent('mouseout', function() {
            el.removeClass('over');
          });
        });
      }
      this.ST = new Sortables(this.eItemsTableBody, sortablesOptions);
      this.dragStarts = false;
      this.orderState = this.ST.serialize().join(',');
      this.ST.addEvent('complete', function(el, clone){
        el.removeClass('move');
        // Если в процессе переноса или если положение не изменилось
        if (!this.dragStarts || this.orderState == this.ST.serialize().join(',')) return;
        el.addClass('loading');
        new Request({
          url: window.location.pathname + '?a=ajax_reorder',
          onComplete: function() {
            this.dragStarts = false;
            this.orderState = this.ST.serialize().join(',');
            el.removeClass('loading');
            this.fireEvent('moveComplete');
          }.bind(this)
        }).POST({
          'ids' : this.ST.serialize(false, function(el){
            return el.get('id').replace('item_', '');
          })
        });    
      }.bind(this));
      this.ST.addEvent('start', function(el, clone){
        this.dragStarts = true;
        el.addClass('move');
      }.bind(this));
      
      if (!this.options.handle) {
        // Подсвечивать строку только в том случае если нет специального бокса дял переноса
        this.eItemsTableBody.addEvents({
          'mousedown': function(e) {
            this.eItemsTableBody.set('styles', {'cursor': 'move'});
          }.bind(this),
          'mouseup': function(e) {
            this.eItemsTableBody.set('styles', {'cursor': 'auto'});
          }.bind(this)
        });
        this.eItemsTableBody.getElements('tr').each(function(el, i){
          el.addEvents({
            'mousedown': function(e) {
              el.addClass('move');
            },
            'mouseup': function(e) {
              el.removeClass('move');
            }
          });
        });
      }
    }
  }
  
});