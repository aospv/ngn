Ngn.cp.ItemsTablePages = new Class({
  Extends: Ngn.ItemsTablePages,
  
  initialize: function(twoScreens, options) {
    this.twoScreens = twoScreens;
    this.parent(options);
  },
  
  init: function() {
    this.parent();
    this.addBtnsActions([
      ['a.editProp', this.twoScreens.openPagePropDialog.bind(this)],
      ['a.editOptions', this.twoScreens.openControllerSettingsDialog.bind(this)]
    ]);
  }
  
});

Ngn.cp.PagesInterface = new Class({

  initialize: function() {
    this.eHandler = $('handler');
    this.eSubNav = $('subNav');
    this.tree = new Ngn.TreeEditPages(
      this,
      'treeContainer',
      {
        buttons: 'treeMenu',
        onTreeLoad: function(){
          this.toggleAll(true);
        }
      }
    ).init();
    if ($('mainContent').hasClass('a_default')) this.initPagesList();
    this.initPanels();
    this.addSubNavBtnAction('a.createModulePage', this.openModuleCreateDialog.pass(parseInt(Ngn.getParam(2, true)), this));
    this.addSubNavBtnAction('a.createPage', this.openCreateDialog.pass(parseInt(Ngn.getParam(2, true)), this));
    this.addSubNavBtnAction('a.editProp', this.openPagePropDialog.pass(Ngn.getParam(2), this));
    this.eSubNav.getElements('a.editOptions').each(function(eBtn) {
      this._addSubNavBtnAction(
        eBtn,
        this.openControllerSettingsDialog.pass(Ngn._getParam(eBtn.get('href'), 2), this)
      );
    }.bind(this));
    this.addSubNavBtnAction('a.add', this.openNewDdItemDialog.bind(this), true);
  },
  
  addSubNavBtnAction: function(selector, action, passBtnElement) {
    var eBtn = this.eSubNav.getElement(selector);
    if (!eBtn) return;
    if (passBtnElement) action = action.pass(eBtn);
    this._addSubNavBtnAction(eBtn, action);
  },
  
  _addSubNavBtnAction: function(eBtn, action) {
    eBtn.addEvent('click', function(e) {
      new Event(e).stop();
      action();
    });
  },
  
  initPanels: function() {
    /*
    var eRight;
    var form = document.getElement('.apeform');
    if ($('itemsTable')) {
      eRight = $('itemsTable');
    } else if (form) {
      eRight = form;
    } else if ($('rightPanel')) {
      eRight = $('rightPanel');
    } else throw new Error('Can not init panels. No right element');
    */
    
    new Ngn.cp.TwoPanels(this.tree.container, $('rightPanel'), this.eHandler, {
      storeId: 'pagesInterface',
      leftExcludeEls: [this.tree.eButtons],
      rightExcludeEls: [this.eSubNav]
    });
  },
  
  openModuleCreateDialog: function(pageId) {
    new Ngn.Dialog.NewModulePage({
      pageId: pageId,
      onSubmitSuccess: function() {
        this.tree.reload();
        //this.reloadItemsList();
      }.bind(this)
    });
  },
  
  openCreateDialog: function(pageId) {
    new Ngn.Dialog.NewPage({
      pageId: pageId,
      onSubmitSuccess: function() {
        this.tree.reload();
        this.reloadItemsList();
      }.bind(this)
    });
  },
  
  openPagePropDialog: function(pageId) {
    new Ngn.Dialog.RequestForm({
      title: 'Редактирование параметров раздела',
      url: Ngn.getPath(2) + '/' + pageId + '/json_editPageProp',
      onSubmitSuccess: function() {
        this.tree.reload();
        this.reloadItemsList();
      }.bind(this)
    });
  },
  
  openControllerSettingsDialog: function(pageId) {
    new Ngn.Dialog.Queue.Request({
      url: Ngn.getPath(2) + '/' + pageId + '?a=json_editControllerSettings',
      width: 600,
      reduceHeight: true
    });
  },
  
  openNewDdItemDialog: function(eBtn) {
    new Ngn.Dialog.RequestForm({
      title: false,
      url: eBtn.get('href').replace('new', 'json_new'),
      onSubmitSuccess: function() {
        window.location = window.location;
      }
    });
  },
  
  initPagesList: function() {
    if (!$('itemsTable')) return;
    this.itemsList = new Ngn.cp.ItemsTablePages(this, {
      onDeleteComplete: function() {
        this.tree.reload();
      }.bind(this),
      onActiveChangeComplete: function() {
        this.tree.reload();
      }.bind(this),
      onMenuChangeComplete: function() {
        this.tree.reload();
      }.bind(this),
      onMoveComplete: function() {
        this.tree.reload();
      }.bind(this)
    });
    this.tree.addEvent('deleteComplete', function(node) {
      this.itemsList.reload();
    }.bind(this));
    this.tree.addEvent('renameComplete', function(node) {
      this.itemsList.reload();
    }.bind(this));
    this.tree.addEvent('moveComplete', function(node) {
      this.itemsList.reload();
      this.tree.reload();
    }.bind(this));
  },
  
  reloadItemsList: function() {
    if (!this.itemsList) return;
    this.itemsList.reload();
  }
  
});
