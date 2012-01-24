Ngn.frm.Page = new Class({
  Implements: [Options],
  
  options: {
    btnPlaceholderClass: 'pageLinkPlaceholder',
    btnClass: 'pageLink',
    actionUrl: '/c/pageTree',
    dropdownOpt: {
      width: 500
    },
    dd: false
  },
  
  setDisabled: function(flag) {
    this.dropdown.disabled = flag;
  },
  
  initialize: function(eRow, options) {
    this.setOptions(options);
    this.initVirtualElement(this);
    this.eInput = eRow.getElement('input');
    this.ePlaceholder = eRow.getElement(this.options.btnPlaceholderClass);
    if (!this.ePlaceholder) {
      var d = ('<div class="'+this.options.btnPlaceholderClass+'"><a href="" class="iconBtn '+this.options.btnClass+'"><i></i></a></div><div class="clear"></div>').toDOM();
      d.each(function(el){
        el.inject(eRow, 'bottom');
      });
      this.ePlaceholder = d[0];
    }
    var inputHightlightFx = new Fx.Tween(this.eInput, { duration: 500 });
    var obj = this;
    this.dropdown = new Ngn.DropdownWin(
      this.ePlaceholder,
      $merge(this.options.dropdownOpt, {
        winClass: 'dropdownWin-pagesTree',
        eParent: this.ePlaceholder.getParent('.dialog'),
        onDomReady: function() {
          this.te = new Ngn.TreeEdit(this.eBody, {
            id: 'pageSelect',
            actionUrl: obj.options.actionUrl,
            enableStorage: false,
            disableDragging: true,
            mifTreeOptions: {
              isDisabledSelect: function(node) {
                return !node.data.dd;
              }
            },
            onDataLoad: function(data) {
              (function() {
                this.eBody.getFirst().setStyle('height', this.bodyInitHeight);
                this.te.tree.addEvent('userSelect', function(node) {
                  // Скрываем DropdownWin
                  this.startHide();
                }.bind(this));
                this.te.tree.addEvent('select', function(node) {
                  if (obj.select(node))
                    inputHightlightFx.start('background-color', '#FFEB8F', '#FFFFFF');
                }.bind(this));
                this.te.openFirstRoot();
              }).delay(100, this);
            }.bind(this)
          }).init();
        }
      })
    );
  },
  
  /**
   * @param Mif.Tree.Node
   * @returns {Boolean}
   */
  select: function(node) {}
  
});

Ngn.frm.Page.implement(Ngn.frm.virtualElement);


Ngn.frm.Page.Link = new Class({
  Extends: Ngn.frm.Page,

  select: function(node) {
    if (!node.data.canLinked) return false;
    this.eInput.set('value', '/' + node.data.path);
    return true;
  }
  
});

Ngn.frm.Page.Id = new Class({
  Extends: Ngn.frm.Page,
  
  options: {
    btnClass: 'pageId',
    dropdownOpt: {
      height: 150
    }
  },
  
  initialize: function(eRow, options) {
    this.parent(eRow, options);
    this.eTitle = new Element('div', {'class': 'dummyFld'}).inject(this.ePlaceholder, 'after');
  },
  
  select: function(node) {
    this.eTitle.set('html', node.data.title + ' <small class="gray">('+node.data.path+')</small>');
    this.eInput.set('value', node.data.id);
    return true;
  }
  
});
