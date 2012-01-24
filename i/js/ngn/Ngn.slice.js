Ngn.slice = {};

Ngn.slice.Layout = new Class({
  
  initialize: function() {
    var offsetY;
    if (Ngn.layout.isHome() && Ngn.layout.homeTopOffset)
      offsetY = Ngn.layout.homeTopOffset;
    Ngn.layout.getElement().getElements('.sliceType_wisiwig').each(function(el, n) {
      var slice = new Ngn.slice.Edit.Tiny(el, Ngn.layout.getPageId());
      if (slice.absoluteSlice && offsetY)
        slice.absoluteSlice.offsetY = offsetY;
    });
    Ngn.layout.getElement().getElements('.sliceType_text').each(function(el, n) {
      new Ngn.slice.Edit.Text(el, Ngn.layout.getPageId());
      if (slice.absoluteSlice && offsetY)
        slice.absoluteSlice.offsetY = offsetY;
    });
  }
  
});

Ngn.slice.Absolute = new Class({
  
  sliceEdit: null, // Ngn.slice.Edit
  offsetY: 0,
  
  initialize: function(sliceEdit) {
    this.sliceEdit = sliceEdit;
    var x = '<button>+</button>'.toDOM()[0].inject(sliceEdit.container);
    var v = '<button>|</button>'.toDOM()[0].inject(sliceEdit.container);
    var h = '<button>-</button>'.toDOM()[0].inject(sliceEdit.container);
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
      handle: x
    }, opt));
    new Drag.Move(sliceEdit.container, $merge({
      modifiers: {'x': '', 'y': 'top'},
      handle: v
    }, opt));
    new Drag.Move(sliceEdit.container, $merge({
      modifiers: {'x': 'left', 'y': ''}, 
      handle: h
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
      'x': this.sliceEdit.container.getStyle('left'),
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
    this.eEditBlock = new Element('div', {'class' : 'editBlock smIcons bordered'})
      .inject(this.container, 'top');
    this.eBtnEdit = new Element('a', {
      'title': 'Редактировать «' + this.getTitle() + '»',
      'class': 'sm-edit',
      'href': '#'
    }).inject(this.eEditBlock);
    new Element('i').inject(this.eBtnEdit);
    this.eText = this.container.getElement('.slice-text');
    this.absolute = container.hasClass('sliceAbsolute');
    if (this.absolute && Ngn.god)
      this.absoluteSlice = new Ngn.slice.Absolute(this);
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
    this.eBtnEdit.addEvent('click', function(e) {
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
    this.eBtnEdit.addEvent('click', function(e) {
      new Ngn.Dialog.Textarea(this.getDialogSettings());
      return false;
    }.bind(this));
  },
  
  isFormat: function() {
    return 0;
  }

});
