//////////////////
////// !!!** /////

Ngn.Pm = {};

Ngn.Pm.Tr = new Class({
  
  initialize: function(eTr, oPM_Table, type) {
    this.eTr = eTr;
    this.oPM_Table = oPM_Table;
    this.type = type;
    this.domain = eTr.get('id').replace('proj_', '');
    this.eTd = eTr.getElement('td[class~='+type+']');
    this.eChk = eTr.getElement('td[class='+type+'_chk]').getElement('input[class=chkSelect]');
    if (!this.eTd) return; // Если нету проекта (нету данных)
    // назначаем на ссылку "параметры" toggle
    this.eTd.getElement('a[class^=btnParams]').addEvent('click', function(e){
      e.preventDefault();
      this.eTr.getElements('div[class=params]').each(function(eParams, i){
        eParams.setStyle('display', eParams.getStyle('display') == 'none' ? 'block' : 'none');
      });
    }.bind(this));
    // Константы
    this.eTd.getElement('div[class~=constants]').getElements('a[class=edit]').each(function(eA, j) {
      eA.addEvent('click', function(e){
        e.preventDefault();
        this.eChk.set('checked', true);
        var AParams = eA.get('id').match(/.+_constant_([^_]+)_(.+)/);
        this.oPM_Table.editConstant(AParams[1], AParams[2]);
      }.bind(this));
    }.bind(this));
    // Логи
    this.eTd.getElement('div[class~=logs]').getElements('a[class=delete]').each(function(eA, j) {
      eA.addEvent('click', function(e){
        e.preventDefault();
        this.eChk.set('checked', true);
        this.oPM_Table.deleteLog(eA.get('id').replace(this.type+'_log_', ''));
      }.bind(this));
    }.bind(this));
  }
});

Ngn.PM.Table = new Class({
  initialize: function(eTable, type) {
    this.type = type;
    this.oPMRows = new Hash({});
    eTable.getElements('tr').each(function(eTr, i) {
      if (eTr.getFirst().get('tag') == 'td') {
        this.oPMRows[eTr.get('id').replace('proj_', '')] = new PM_TR(eTr, this, type);
      }
    }.bind(this));    
    var eChks = new Hash({});    
    eTable.getElements('td[class='+type+'_chk]').each(function (eTdChk, i) {
      eChks[i] = eTdChk.getElement('input[class=chkSelect]');
    });
    this.eChks = eChks;
    
    var eBtnSelectAll = $(this.type+'_btnSelectAll');
    eBtnSelectAll.addEvent('change', function() {
      var flag = eBtnSelectAll.get('checked');
      this.eChks.each(function(eChk, i) {
        eChk.set('checked', flag);
      });
    }.bind(this));
  },
  editConstant: function(name, constant) {
    var n = 0;
    this.eChks.each(function(eChk, i) {
      if (eChk.get('checked')) {
        n++;
      }
    });
    if (n > 1) {
      if (!confirm('Вы действительно хотите редактировать эту константу "'+constant+'" для всех выбранных проектов ('+n+' шт.). Если да - нажмите OK, если нет - нажмите Cancel и снимите выделение с других проектов.')) {
        return;
      }
    }
    var v = prompt('Введите значение:', $(this.type+'_constant_'+name+'_'+constant).get('title'));
    if (!v) return;
    new Request({
      method: 'post',
      url: window.location.pathname,
      data: {
        action: 'ajax_grpUpdateConstant',
        domains: this.getSelectedDomains(),
        type: this.type,
        name: name,
        k: constant,
        v: v
      },
      onComplete: function(data) {
        this.getSelectedDomains().each(function(domain, i){
          this.refrashTd(domain, true);
        }.bind(this));
      }.bind(this)
    }).send();  
  },
  deleteLog: function(name) {
    var n = 0;
    this.eChks.each(function(eChk, i) {
      if (eChk.get('checked')) n++;
    });
    if (n > 1) {
      if (!confirm('Вы действительно хотите удалить лог-файл "'+name+'.log" у всех выбранных проектов ('+n+' шт.). Если да - нажмите OK, если нет - нажмите Cancel и снимите выделение с других проектов.')) {
        return;
      }
    }
    new Request({
      method: 'post',
      url: window.location.pathname,
      data: {
        action: 'ajax_grpDeleteLog',
        domains: this.getSelectedDomains(),
        type: this.type,
        name: name
      },
      onComplete: function(data) {
        window.location = window.location.pathname;
      }
    }).send();  
  },  
  getSelectedDomains: function() {
    var domains = new Array();
    var i = 0;
    this.oPMRows.each(function(oPMRow, k){
      if (oPMRow.eChk.get('checked')) {
        domains[i] = oPMRow.domain;
        i++;
      }
    });
    return domains;
  },
  refrashTd: function(domain, openParams) {
    var projDataTd = $('proj_'+domain).getElement('td[class~='+this.type+'_projData]');
    projDataTd.addClass('loader');
    new Request({
      method: 'get',
      url: window.location.pathname,
      data: {
        action: 'ajax_projTdData',
        domain: domain,
        type: this.type
      },
      onComplete: function(html) {
        projDataTd.removeClass('loader');
        projDataTd.set('html', html);
        if (openParams) projDataTd.getElement('div[class=params]').setStyle('display', 'block');
        this.oPMRows[domain] = new PM_TR($('proj_'+domain), this, this.type);
      }.bind(this)
    }).send();
  }
});

function bindCopy(key) {
  // Кнопки копирования проекта
  $('items').getElements('tr').each(function(eTr, i) {
    if (eTr.getFirst().get('tag') == 'td') {
      var domain = eTr.get('id').replace('proj_', '');
      eTr.getElement('a[class='+key+']').addEvent('click', function(e){
        e.preventDefault();
        if (!confirm('Вы действительно хотите переписать проект «'+domain+'» с '+
          (key == 'copyTestToProd' ? 'Теста на Продакшн' : 
          'Продакшна на Теста н')+'?')) return;
        
        var eCopyBtnsTD = $('copyBtns_'+domain);
        eCopyBtnsTD.addClass('loader');
        new Request({
          method: 'get',
          url: window.location.pathname,
          data: {
            action: 'ajax_'+key,
            domain: domain
          },
          onComplete: function(data) {
            eCopyBtnsTD.removeClass('loader');
            key == 'copyProdToTest' ? tables['testing'].refrashTd(domain, false) :
                                      tables['production'].refrashTd(domain, false)
          }
        }).send();  
      });
    }
  });
}

var tables = new Hash({});

window.addEvent('domready',function() {
  tables['testing'] = new PM_Table($('items'), 'testing');
  tables['production'] = new PM_Table($('items'), 'production');
  bindCopy('copyProdToTest');
  bindCopy('copyTestToProd');
});
