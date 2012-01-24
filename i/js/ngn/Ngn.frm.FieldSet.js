/**
 * 
 * <div id="mainElement">
 *   <div class="rowElement">
 *     <input type="" name="k[0]" value="gg" size="40" id="k[0]i" />
 *     <input type="" name="v[0]" value="gggg" size="40" id="v[0]i" />
 *     <div class="drag"></div>
 *     <a href="#" class="smIcons sm-delete bordered"><i></i></a>
 *     <div class="clear"><!-- --></div>
 *   </div>
 *   <div class="element">
 *     ...
 *   </div>
 *   <a href="#" class="add">Добавить значение</a>
 * </div>
 * 
 */
Ngn.frm.fieldSets = [];

Ngn.frm.FieldSet = new Class({
  
  Implements: [Options, Events],
  
  options: {
    rowElementSelector: 'div[class~=rowElement]',
    elementContainerSelector: '.element',
    cleanOnCloneSelector: '.type_image .iconsSet',
    addRowBtnSelector: 'a[class~=add]',
    deleteBtnSelector: 'a[class~=sm-delete]',
    dragBoxSelector: 'div[class=dragBox]',
    removeExceptFirstRow: 'p.label',
    moveElementToRowStyles: ['border-bottom', 'padding-left'],
    addTitle: 'Добавить значение',
    cleanupTitle: 'Очистить поля строки',
    deleteTitle: 'Удалить строку'
  },
  
  changed: false,
  eSampleRow: null,
  buttons: [], // array of Ngn.IconBtn objects
  form: null, // Ngn.Form
  
  setDisabled: function(flag) {
    for (var i=0; i<this.buttons.length; i++) {
      this.buttons[i].toggle(!flag);
    }
  },

  initialize: function(form, container, options) {
    this.form = form;
    Ngn.frm.fieldSets.include(this);
    this.initVirtualElement();
    this.eContainer = $(container);
    this.setOptions(options);
    this.eAddRow = this.eContainer.getElement(this.options.addRowBtnSelector);
    if (!this.eAddRow) {
      this.eAddRow = Ngn.iconBtn(this.options.addTitle, 'add gray').
        inject(this.eContainer, 'bottom');
      '<div class="heightFix"></div>'.toDOM()[0].inject(this.eContainer, 'bottom');
    }
    this.buttons.push(new Ngn.IconBtn(this.eAddRow, function(btn){
      this.buttons.push(btn);
      this.addRow();
    }.bind(this)));
    this.initRows();
    //this.initSorting();
    this.checkDeleteButtons();
  },
  
  /*
  inputsEmpty: function(container) {
    var elements = container.getElements('input')
    for (var i = 0; i < elements.length; i++) {
      if (elements[i].get('value')) return false; 
    }
    return true;
  },
  */
  
  initRows: function() {
    var esRows, esEls, style;
    if (!this.options.rowElementSelector) {
      this.eContainer.getElements('input').each(function(eInput){
        var eRowDiv = new Element('div', {'class': 'genRow'})
        eRowDiv.inject(eInput, 'after');
        eInput.inject(eRowDiv);
      });
      this.options.rowElementSelector = 'div[class=genRow]';
    }
    // Переносим стили элементов в стили контейнеров элементов, а у элементов их удаляем
    esRows = this.eContainer.getElements(this.options.rowElementSelector);
    this.eSampleRow = esRows[0].clone();
    this.eSampleRow.getElements(this.options.cleanOnCloneSelector).dispose();
    this.createCleanupButton(esRows[0]);
    this.removeTrash(this.eSampleRow);
    for (var i=0; i<esRows.length; i++) this.moveStyles(esRows[i]);
    if (esRows.length > 0) {
      for (var i=1; i<esRows.length; i++) {
        this.removeTrash(esRows[i]);
        this.createDeleteButton(esRows[i]);
      }
    }
  },
  
  moveStyles: function(eRow) {
    var style;
    esEls = eRow.getElements(this.options.elementContainerSelector);
    for (var j=0; j<this.options.moveElementToRowStyles.length; j++) {
      style = this.options.moveElementToRowStyles[j];
      eRow.setStyles(esEls[0].getStyles(style));
      for (var k=0; k<esEls.length; k++)
        esEls[k].setStyle(style, '0');
    }
  },

  checkDeleteButtons: function() {
    return;
    // Удаляем кнопку "Удалить", если элемент 1 в списке и значения полей пустые
    if (this.eRows.length == 1) {
      var eRow = this.eContainer.getElement(this.options.rowElementSelector);
    }
  },
  
  removeTrash: function(eRow) {
    eRow.getElements(this.options.removeExceptFirstRow).each(function(el) {
      el.dispose();
    });
  },

  createDeleteButton: function(eRow) {
    var els = eRow.getElements(this.options.elementContainerSelector);
    this.buttons.push(new Ngn.IconBtn(
      // Вставляем кнопку после последнего элемента формы в этой строке 
      Ngn.iconBtn('', 'delete bordered', {
        title: this.options.deleteTitle
      }).inject(els[els.length-1], 'after'),
      function(btn) {
        eRow.dispose();
        this.regenInputNames();
        this.fireEvent('delete');
        this.buttons.erase(btn);
      }.bind(this)
    ));
  },
  
  createCleanupButton: function(eRow) {
    var els = eRow.getElements(this.options.elementContainerSelector);
    var eLabel = eRow.getElement(this.options.removeExceptFirstRow);
    var eBtn = Ngn.iconBtn('', 'cleanup bordered', {
      title: this.options.cleanupTitle
    }).inject(els[els.length-1], 'after');
    if (eLabel) eBtn.setStyle('margin-top',
      (eBtn.getStyle('margin-top').toInt() + eLabel.getSizeWithMargin().y) + 'px');
    this.buttons.push(new Ngn.IconBtn(
      // Вставляем кнопку после последнего элемента формы в этой строке 
      eBtn,
      function() {
        eRow.getElements(Ngn.frm.selector).set('value', '');
        this.fireEvent('cleanup');
      }.bind(this)
    ));
  },
  
  addRow: function() {
    var eLastRow = this.eContainer.getLast(this.options.rowElementSelector);
    var eNewRow = this.eSampleRow.clone();
    eNewRow.getElements(Ngn.frm.selector).each(function(eInput) {
      eInput.set('value', '');
      eInput.set('name', this.getInputName(eInput, this.getNextN(eLastRow)));
    }.bind(this));
    eNewRow.injectAfter(eLastRow);
    this.createDeleteButton(eNewRow);
    this.fireEvent('addRow');
    this.moveStyles(eNewRow);
    this.form.addElements(eNewRow);
    // this.initSorting();
  },
  
  getNextN: function(eRow) {
    var els = eRow.getElements(Ngn.frm.selector);
    var name;
    for (var i=0; i<els.length; i++) {
      name = els[i].get('name');
      if (name) break;
    }
    return name.replace(/.*\[(\d)+\].*/, '$1').toInt() + 1;
  },

  getInputName: function(eInput, n) {
    var name = eInput.get('name');
    if (!name) return;
    return name.replace(/([a-z0-9]+)\[([0-9]+)\](.*)/i, '$1['+n+']$3');
  },
  
  regenInputNames: function() {
    this.eContainer.getElements(this.options.rowElementSelector).each(function(eRow, n) {
      eRow.getElements(Ngn.frm.selector).each(function(eInput) {
        eInput.set('name', this.getInputName(eInput, n));
      }.bind(this));
    }.bind(this));
  },

  initSorting: function() {
    var ST = new Sortables(this.eContainer, {
      handle: this.options.dragBoxSelector
    });
    ST.addEvent('start', function(el, clone){
      el.addClass('move');
    });
    ST.addEvent('complete', function(el, clone){
      el.removeClass('move');
    }.bind(this));
    
    this.eContainer.getElements(this.options.dragBoxSelector).each(function(el) {
      el.addEvent('mouseover', function() {
        el.addClass('over');
      });
      el.addEvent('mouseout', function() {
        el.removeClass('over');
      });
    });
  }
  
});

Ngn.frm.FieldSet.implement(Ngn.frm.virtualElement);
