Ngn.slice = {};

Ngn.slice.Layout = new Class({
  
  initialize: function() {
    if (Ngn.layout.isHome() && Ngn.layout.homeTopOffset)
      this.offsetY = Ngn.layout.homeTopOffset;
    this.initElements();
    this.initEdit();
  },
  
  initElements: function() {
    this.elements = document.getElements('.slice');
  },
  
  initEdit: function() {
    this.elements.each(function(el, n) {
      if (Ngn.isGod || (el.hasClass('allowAdmin') && Ngn.isAdmin)) {
        //c(el);
        if (el.hasClass('sliceType_text'))
          var slice = new Ngn.slice.Edit.Text(el);
        else
          var slice = new Ngn.slice.Edit.Tiny(el);
        if (slice.absoluteSlice && this.offsetY)
          slice.absoluteSlice.offsetY = this.offsetY;
      }
    }.bind(this));
  }
  
});

Ngn.slice.Absolute = new Class({
  
  sliceEdit: null, // Ngn.slice.Edit
  offsetY: 0,
  
  initialize: function(sliceEdit) {
    this.sliceEdit = sliceEdit;
    var btnMove = this.sliceEdit.eEditBlock.getElement('.sm-move');
    this.sliceEdit.container.addClass('allowDrag');
    var opt = {
      onSnap: function() {
        this.sliceEdit.container.addClass('dragging')
      }.bind(this),
      onComplete: function() {
        this.save();
        this.sliceEdit.container.removeClass('dragging');
      }.bind(this)
    };
    new Drag.Move(sliceEdit.container, $merge({
      modifiers: {'x': 'left', 'y': 'top'},
      handle: btnMove
    }, opt));
  },
  
  save: function() {
    this.sliceEdit.container.addClass('loader');
    new Request({
      url: '/c/slice?a=ajax_savePos',
      onComplete: function() {
        this.sliceEdit.container.removeClass('loader');
      }.bind(this)
    }).POST({
      'id': this.sliceEdit.id,
      'x': parseInt(this.sliceEdit.container.getStyle('left')),
      'y': parseInt(this.sliceEdit.container.getStyle('top')) - this.offsetY
    });
  }
  
});

Ngn.slice.Edit = new Class({
  Implements: [Options],
  
  options: {
    //absoluteOptions: { offset: { x: 0, y: 0 } }
  },
  
  initialize: function(container) {
    this.id = container.get('id').replace(/^slice_(.*)/, '$1');
    // Если блок глобальный, используем нулевой ID раздела,
    // т.о. блок принадлежит всем разделам
    if (container.get('class').match(/slice_global/)) {
      this.pageId = 0;
    } else {
      if (!this.id.match(/\w+_(\d+).*/))
        alert('Wrong is format for non-global slice: ' + 
        this.id +'. Must be: ' + this.id + '_pageId');
      this.pageId = this.id.replace(/[^_]+_(\d+).*/, '$1');
    }
    this.container = container;
    this.container.addClass('editable');
    this.eEditBlock = Ngn.smBtns([{
      href: '#',
      title: 'Редактировать «' + this.getTitle() + '»',
      name: 'edit'
    }], true).addClass('editBlock').inject(this.container, 'top');
    Ngn.setToTopRight(this.eEditBlock);
    
    this.eText = this.container.getElement('.slice-text');
    this.absolute = container.hasClass('sliceAbsolute');
    if (this.absolute && Ngn.isGod)
      this.absoluteSlice = new Ngn.slice.Absolute(this);
    
    this.btnEdit = this.eEditBlock.getElement('.sm-edit');
    this.initEditBtn();
    
  },
  
  getTitle: function() {
    return this.container.getElement('.slice-title').get('html');
  },
  
  editorOk: function(_html) {
    this.eText.set('html', _html);
    new Request({
      url: '/c/slice?a=ajax_save',
      onComplete: function(html) {
        this.eText.set('html', html);
      }.bind(this)
    }).POST({
      //'groupName': this.groupName,
      'id': this.id,
      'pageId': this.pageId,
      'title': this.getTitle(),
      'text': _html,
      'absolute': this.absolute ? 1 : 0,
      'format': this.isFormat()
    });
  },
  
  getDialogSettings: function() {
    return {
      'id': this.id,
      'draggable': true,
      'force': true,
      'width': '600px',
      'title': this.getTitle() + ': редактирование',
      'message': this.eText.get('html'),
      'callback': this.editorOk.bind(this)
    };
  }
  
});

Ngn.slice.Edit.Tiny = new Class({
  Extends: Ngn.slice.Edit,
  
  initEditBtn: function() {
    this.btnEdit.addEvent('click', function(e) {
      new Ngn.Dialog.Tiny(this.getDialogSettings());
      return false;
    }.bind(this));
  },
  
  isFormat: function() {
    return 1;
  }  

});

Ngn.slice.Edit.Text = new Class({
  Extends: Ngn.slice.Edit,
  
  initEditBtn: function() {
    this.btnEdit.addEvent('click', function(e) {
      new Ngn.Dialog.Textarea(this.getDialogSettings());
      return false;
    }.bind(this));
  },
  
  isFormat: function() {
    return 0;
  }

});
