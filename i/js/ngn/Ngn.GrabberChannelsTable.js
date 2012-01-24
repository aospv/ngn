Ngn.GrabberChannelsTable = new Class({

  Extends: Ngn.ItemsTable,
  
  init: function() {
    this.parent();
    // ------------------
    this.eItemsTableBody.getElements('tr').each(function(eTr, n){
      
      //var a1 = parseInt(eTr.getElement('.iconBtnCaptionLinks').get('html').replace(/(\d+)\/(\d+)/, '$1'));
      //var a2 = parseInt(eTr.getElement('.iconBtnCaptionLinks').get('html').replace(/(\d+)\/(\d+)/, '$2'));
      
      // Кнопка "Редактировать"
      eTr.getElement('a.edit').addEvent('click', function() {
        new Ngn.Dialog.RequestForm({
          url: Ngn.getPath(3)+'/json_edit?id='+this.getId(eTr),
          onSubmitSuccess: function() {
            window.location.reload(true);
          }.bind(this)
        });
        return false;
      }.bind(this));
      
      // Кнопка "Тестировать"
      eTr.getElement('a.test').addEvent('click', function() {
        this.openTestDialog(eTr);
        return false;
      }.bind(this));
      
      // Кнопка "Импортировать новое"
      eTr.getElement('a.import').addEvent('click', function() {
        this.importNew(eTr);
        return false;
      }.bind(this));
      
      // Кнопка "Импортировать всё. Сохранить ссылки"
      var btnSaveLinks = eTr.getElement('a.saveLinks');
      if (btnSaveLinks) btnSaveLinks.addEvent('click', function() {
        this.importAll(eTr);
        return false;
      }.bind(this));
      
      // Кнопка "Импортировать всё"
      var btnImportAll = eTr.getElement('a.importAll');
      if (btnImportAll) btnImportAll.addEvent('click', function() {
        this._importAll(eTr);
        return false;
      }.bind(this));
      
      // Кнопка "Удалить ссылки"
      var btnDeleteLinks = eTr.getElement('a.deleteLinks');
      if (btnDeleteLinks) {
        //if (!a1) {
        //  btnDeleteLinks.dispose();
        //} else {
          btnDeleteLinks.addEvent('click', function() {
            this.deleteLinks(eTr);
            return false;
          }.bind(this));
        //}
      }
      
      // Кнопка "Удалить импортированые записи"
      var btnDeleteAllImported = eTr.getElement('a.deleteAllImported');
      if (btnDeleteAllImported) btnDeleteAllImported.addEvent('click', function() {
        this.deleteAllImported(eTr);
        return false;
      }.bind(this));
      
      // Обратный отсчет
      var eNextGrabTime = eTr.getElement('.nextGrab');
      new MooCountdown.Date(eNextGrabTime, {
        date: eNextGrabTime.get('text'),
        text: [':', ':', ':', ':', ''], 
        startFont: 12, 
        finishFont: 12 
      });
      
    }.bind(this));
  },
  
  openTestDialog: function(eTr) {
    new Ngn.Dialog({
      title: '<b>Тестрирование:</b> ' + eTr.getElement('.title').get('text'),
      url: Ngn.getPath(3) + '?a=ajax_test&id=' + this.getId(eTr),
      width: 600,
      footer: false
    });
  },

  importNew: function(eTr) {
    new Ngn.Request.JSON({
      url: window.location.pathname + '?a=json_importNew'+
        '&id=' + this.getId(eTr),
      onComplete: function(data) {
        eTr.removeClass('loading');
        if (!data) {
          alert('При импорте возникли ошибки');
          return;
        }
        if (data.error) {
          alert(data.error);
          return;
        }
        if (!data.importedCount)
          alert("Новых записей нет");
        else
          alert("Получено" + data.importedCount + " записей");
        if (data.importedCount) {
          // Если испортированы записи перезагружаем интерфейс
          this.reload(eTr);
        }
      }.bind(this)
    }).send();
  },
  
  importAll: function(eTr) {
    this.saveLinks(
      eTr,
      this._importAll
    );
  },
  
  _importAll: function(eTr) {
    new Ngn.PartialJob(
      Ngn.getPath() + window.location.pathname + '?a=json_importAll'+
        '&id=' + this.getId(eTr),
      {
        closeOnComplete: true,
        loaderTitleStart: 'Импортируем записи',
        //loaderTitleStep: 'Осталось импортиро: %remains из %total%text',
        onError: function() {
          eTr.removeClass('loading');
        },
        //onComplete: function(r) {
        //}.bind(this),
        onStop: function(r) {
          eTr.removeClass('loading');
          this.reload(eTr);
        }.bind(this),
      }
    ).start();            
  },
  
  saveLinks: function(eTr, completeFunc) {
    eTr.addClass('loading');
    new Ngn.PartialJob(
      Ngn.getPath() + window.location.pathname + '?a=json_saveLinks'+
        '&id=' + this.getId(eTr),
      {
        closeOnComplete: true,
        unknownTotalCount: eTr.hasClass('unknownTotalCount'),
        loaderTitleStart: 'Сохраняем ссылки на страницы всех записей',
        loaderTitleStep: 'Осталось обработать страниц: {jobsRemains} из {jobsTotal}{text}',
        loaderTitleStepUnknown: '{step} страниц обработано',
        onError: function() {
          eTr.removeClass('loading');
        },
        onComplete: function(r) {
          (function () {completeFunc.run(eTr, this)}.bind(this)).delay(1000);
        }.bind(this),
        onStop: function(r) {
          eTr.removeClass('loading');
          this.reload(eTr);
        }.bind(this)
      }
    ).start();
  },
  
  deleteLinks: function(eTr) {
    if (!confirm('Вы уверены?')) return;
    eTr.addClass('loading');
    new Request({
      url: window.location.pathname + '?a=ajax_deleteLinks&id='+this.getId(eTr),
      onComplete: function(r) {
        eTr.removeClass('loading');
        this.reload(eTr);
      }.bind(this)
    }).send();
  },
  
  deleteAllImported: function(eTr) {
    if (!confirm('Вы уверены?')) return;
    eTr.addClass('loading');
    new Request({
      url: window.location.pathname + '?a=ajax_deleteAllImported&id='+this.getId(eTr),
      onComplete: function(r) {
        eTr.removeClass('loading');
        this.reload(eTr);
      }.bind(this)
    }).send();
  },
  
  afterReload: function(eTr) {
    var fx = new Fx.Tween(eTr.get('id'), {
      duration: 200,
      onComplete: function() {
        fx.setOptions({duration: 3000});
        fx.start('background-color', '#FFFFFF');
      }
    });
    fx.start('background-color', '#FFB900');
  }
  
});
