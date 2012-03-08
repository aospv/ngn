var PrivMsgs = new Class({
  initialize: function(url) {
    this.url = url;
  },
  getClassName: function() {
    return 'PrivMsgs';
  },
  send: function(toUserId, text) {
    new Request({
      url: this.url,
      onComplete: function() {
        this.sendComplete();
      }.bind(this)
    }).POST({
      'action' : 'ajaxSend',
      'toUserId' : toUserId,
      'text' : text
    });
  },
  sendComplete: function(data) {
  },
  getMsgs: function(toUserId) {
    new Request({
      url: this.url,
      onComplete: function(data) {
        this.getMsgsComplete(data);
      }.bind(this)
    }).GET({
      'action' : 'ajaxGetMsgs',
      'toUserId' : toUserId
    });
  },
  getMsgsComplete: function(data) {
  },
  getNewMsgs: function(toUserId) {
    new Ngn.Request.JSON({
      url: this.url,
      onComplete: function(data) {
        this.getNewMsgsComplete(data);
      }.bind(this)
    }).GET({
      'action' : 'ajaxGetNewMsgs',
      'toUserId' : toUserId
    });

  },
  getNewMsgsComplete: function(data) {
  },
  deleteChat: function(toUserId) {
    new Request({
      url: this.url,
      onComplete: function(data) {
        this.deleteChatComplete(data);
      }.bind(this)
    }).GET({
      'action' : 'ajaxDeleteChat',
      'toUserId' : toUserId
    });
  },
  deleteChatComplete: function() {
  },
  deleteMsgs: function(msgIds) {

    new Request({
      url: this.url,
      onComplete: function(data) {
        this.deleteMsgsComplete(data);
      }.bind(this)
    }).POST({
      'action' : 'ajaxDeleteMsgs',
      'msgIds' : JSON.encode(msgIds)
    });

  },
  deleteMsgsComplete: function() {

  }
});

////////////////////////////////////////////

var PrivMsgsLayout = new Class({
  Extends: PrivMsgs,
  getClassName: function() {
    return 'PrivMsgsLayout';
  },
  send: function() {
    this.checkActiveSendBtn();
    if (!this.sendActive) return;
    this.deactivateSendBtn();
    this.deactivateTextarea();
    this.setLoadingSendBtn();
    this.parent(this.toUserId, this.msgTextarea.value);
    this.sendTimeoutId = this.sendTimeout.delay(this.timeout, this);
  },
  sendComplete: function(data) {
    $clear(this.sendTimeoutId);
    this.activateTextarea();
    this.activateSendBtn();
    this.msgTextarea.value = '';
    this.msgTextarea.focus();
    this.getNewMsgs();
  },
  sendTimeout: function() {
    this.activateTextarea();
    this.activateSendBtn();
    alert('Истекло время выполнения запроса');
  },
  deleteChat: function() {
    this.parent(this.toUserId);
  },
  deleteChatComplete: function() {
    this.chat.innerHTML = '';
  },
  activateSendBtn: function() {
    this.sendMsgBtn.addClass('active');
    this.sendActive = true;
  },
  deactivateSendBtn: function() {
    this.sendMsgBtn.removeClass('active');
    this.sendActive = false;
  },
  activateTextarea: function() {
    //this.msgTextarea.removeClass('deactive');
    this.msgTextarea.setProperty('disabled', false);
  },
  deactivateTextarea: function() {
    //this.msgTextarea.addClass('deactive');
    this.msgTextarea.setProperty('disabled', true);
  },
  setLoadingSendBtn: function() {
    this.sendMsgBtn.addClass('loading');
  },
  removeLoadingSendBtn: function() {
    this.sendMsgBtn.removeClass('loading');
  },
  checkActiveSendBtn: function() {
    if (this.msgTextarea.value == '') this.deactivateSendBtn();
    else this.activateSendBtn();
  },
  getMsgs: function() {
    this.parent(this.toUserId);
  },
  getMsgsComplete: function(data) {
    this.chat.set('html',data);
    this.scrollBottom.delay(10, this);
  },
  getNewMsgs: function() {
    this.parent(this.toUserId);
  },
  getNewMsgsComplete: function(data) {
    if (!data) return;
    this.chat.set('html',this.chat.get('html') + data.msgs);
    $('chatBan').setStyle('display', data.isBan ? '':'none');
    this.scrollBottom.delay(10, this);
    if (data.isNewMsgs) {
      //window.focus();
      this.playAlertSound();
    }
  },
  scrollBottom: function() {
    this.chat.scrollTop = 10000000;
  },

  startRefrash: function() {
    this.refrashFuncId = this.getNewMsgs.periodical(this.refrashTime, this);
    Cookie.write('refrash', 1);
  },
  stopRefrash: function() {
    $clear(this.refrashFuncId);
    Cookie.write('refrash', 0);
  },

  alertSoundOn: function() {
    this.isAlertSound = true;
    Cookie.write('alertSound', 1);
  },
  alertSoundOff: function() {
    this.isAlertSound = false;
    Cookie.write('alertSound', 0);
  },

  playAlertSound: function() {
    if (!this.isAlertSound) return;
    this.alertSound.setStyle('display', 'block');
    this.stopAlertSound.delay(1000, this);
  },
  stopAlertSound: function() {
    this.alertSound.setStyle('display', 'none');
  },
  initialize: function(url, toUserId) {
    this.parent(url);
    this.timeout = 10000;
    this.refrashTime = 10000;
    this.refrash = false;
    this.toUserId = toUserId;
    this.msgTextarea = $('msgText');
    this.sendMsgBtn = $('sendMsgBtn');
    this.refrashBtn = $('refrash');
    this.alertSoundBtn = $('soundBtn');
    this.alertSound = $('alertSound');
    this.chat = $('chat');
    this.smiles = $('smiles');
    this.sendActive = false;
    this.refrashFuncId;
    //this.order = 'asc';
    this.msgTextarea.addEvent('keydown', function(e){
      if (e.code==13 && e.control) {
        this.send();
      }
    }.bind(this));
    this.msgTextarea.addEvents({
      'keyup':   function(e){ this.checkActiveSendBtn(); }.bind(this),
      'mouseup': function(e){ this.checkActiveSendBtn(); }.bind(this)
    });
    this.smiles.addEvent('click', function(e){
      this.checkActiveSendBtn();
    }.bind(this));
    this.msgTextarea.addEvent('click', function(e){
      this.checkActiveSendBtn.delay(1000, this);
    }.bind(this));
    this.sendMsgBtn.addEvent('click', function(e){
      e.stop();
      this.send();
    }.bind(this));

    this.refrashBtn.addEvent('change', function(){
      this.getNewMsgs();
      this.refrashBtn.checked ? this.startRefrash() : this.stopRefrash();
    }.bind(this));

    this.alertSoundBtn.addEvent('change', function(){
      this.alertSoundBtn.checked ? this.alertSoundOn() : this.alertSoundOff();
    }.bind(this));

    this.getMsgs();
    this.msgTextarea.focus();

    // init refrash
    var refrashCookie = Cookie.read('refrash');
    if (refrashCookie != 1 && refrashCookie != 0) refrashCookie = 1;
    this.refrashBtn.checked = refrashCookie == 1 ? true : false;
    this.refrashBtn.checked ? this.startRefrash() : this.stopRefrash();

    // init alert sound
    var alertSoundCookie = Cookie.read('alertSound');
    if (alertSoundCookie != 1 && alertSoundCookie != 0) alertSoundCookie = 1;
    this.alertSoundBtn.checked = alertSoundCookie == 1 ? true : false;
    this.alertSoundBtn.checked ? this.alertSoundOn() : this.alertSoundOff();

    new Swiff('/img/privMsgs/alert.swf?1', {
      width:  1,
      height: 1,
      container: this.alertSound
    });

  }
});

////////////////////////////////////////////

var PrivMsgsHistoryLayout = new Class({
  Extends: PrivMsgs,

  initialize: function(url, toUserId) {
    this.parent(url);
    this.toUserId = toUserId;
    this.checkAllBtn = $('checkAll');
    this.checkBtns = new Array();
    this.allSelected = false;
    $('deleteHistory').addEvent('click', function() {
      if (this.allSelected) this.deleteChat();
      else this.deleteMsgs();
    }.bind(this));
    this.checkAllBtn.addEvent('click', function() {
      if (this.allSelected) {
        this.checkAllBtn.set('text','Отметить все');
        this.allSelected = false;
        this.deselectAll();
      } else {
        this.checkAllBtn.set('text','Снять выделение');
        this.allSelected = true;
        this.selectAll();
      }
    }.bind(this));
    this.initCheckboxes();
  },

  getMsgs: function() {
    new Request({
      url: this.url,
      onComplete: function(data) {
        this.getMsgsComplete(data);
      }.bind(this)
    }).GET({
      'action' : 'ajaxGetHistory',
      'toUserId' : this.toUserId
    });
  },

  getMsgsComplete: function(data) {
    $('history').set('html',data);
    this.initCheckboxes();
  },

  deleteChat: function() {
    this.parent(this.toUserId);
  },

  deleteChatComplete: function() {
    $('history').set('html','');
  },

  deleteMsgs: function() {

    this.parent(this.getMsgIds());
  },

  deleteMsgsComplete: function(data) {
    this.getMsgs();
  },

  getMsgIds: function() {
    var msgIds = new Array();
    var i = 0;
    $('history').getElements('input[type=checkbox]').each(function (checkbox, i) {
      if (checkbox.getProperty('checked')) {
        msgIds[i] = checkbox.getProperty('value');
      }
    });
    return msgIds;
  },

  selectAll: function() {
    $('history').getElements('input[type=checkbox]').each(function (checkbox, i) {
      checkbox.setProperty('checked', true);
    });
  },

  deselectAll: function() {
    $('history').getElements('input[type=checkbox]').each(function (checkbox, i) {
      checkbox.setProperty('checked', false);
    });
  },

  initCheckboxes: function() {
    $('history').getElements('input[type=checkbox]').each(function (checkbox, i) {
      checkbox.addEvent('click', function(e){
        this.allSelected = false;
      }.bind(this));
    }.bind(this));
  }

});

////////////////////////////////////////////

var Contacts = new Class({
  initialize: function(url) {
    this.url = url;
  },
  addContact: function(contactId) {
    new Request({
      url: this.url,
      onComplete: function() {
        this.addContactComplete();
      }.bind(this)
    }).GET({
      'action' : 'ajaxAddContact',
      'contactId' : contactId
    });
  },
  addContactComplete: function() {
  },
  getContacts: function() {
    new Request({
      url: this.url,
      onComplete: function(data) {
        this.getContactsComplete(data);
      }.bind(this)
    }).GET({
      'action' : 'ajaxGetContacts'
    });
  },
  getContactsComplete: function() {
  },
  deleteContact: function(contactId) {
    new Request({
      url: this.url,
      onComplete: function() {
        this.deleteContactComplete();
      }.bind(this)
    }).GET({
      'action' : 'ajaxDeleteContact',
      'contactId' : contactId
    });
  },
  deleteContactComplete: function() {
  },
  getFriendsQueue: function() {
    new Request({
      url: this.url,
      onComplete: function(data) {
        this.getFriendsQueueComplete(data);
      }.bind(this)
    }).GET({
      'action' : 'ajaxGetFriendsQueue'
    });
  },
  getFriendsQueueComplete: function() {
  },
  addFriend: function(contactId) {
    new Request({
      url: this.url,
      onComplete: function() {
        this.addFriendComplete();
      }.bind(this)
    }).GET({
      'action' : 'ajaxAddFriend',
      'contactId' : contactId
    });
  },
  addFriendComplete: function() {
  },
  deleteFriend: function(contactId) {
    new Request({
      url: this.url,
      onComplete: function() {
        this.addFriendComplete();
      }.bind(this)
    }).GET({
      'action' : 'ajaxDeleteFriend',
      'contactId' : contactId
    });
  },
  deleteFriendComplete: function() {
  },
  acceptFriend: function(contactId) {
    new Request({
      url: this.url,
      onComplete: function() {
        this.acceptFriendComplete();
      }.bind(this)
    }).GET({
      'action' : 'ajaxAcceptFriend',
      'contactId' : contactId
    });
  },
  acceptFriendComplete: function() {
  },
  declineFriend: function(contactId) {
    new Request({
      url: this.url,
      onComplete: function() {
        this.declineFriendComplete();
      }.bind(this)
    }).GET({
      'action' : 'ajaxDeclineFriend',
      'contactId' : contactId
    });
  },
  declineFriendComplete: function() {
  }
});

////////////////////////////////////////////

var ContactsLayout = new Class({
  Extends: Contacts,
  initialize: function(url) {
    this.parent(url);
    $('userSearchBtn').addEvent('click', function(e){
      e.preventDefault();
      this.searchUser();
    }.bind(this));
    $('userMask').addEvent('keydown', function(e){
      if (e.code==13) this.searchUser();
    }.bind(this));
    $('userMask').focus();
    this.setContextMenuContacts();
    this.setFriendsQueueBtns();
  },
  searchUser: function() {
    var obj = this;
    var userMask = $('userMask').getProperty('value');
    var results = $('results');
    if (!userMask) return;
    results.addClass('loader');
    new Request({
      url: this.url,
      onComplete: function(data) {
        results.innerHTML = data;
        results.removeClass('loader');
        obj.setContextMenuSearch();


        /*
        results.getElements('a[class=addContact]').each(function (btn, i) {
          btn.setProperty('title', 'Добавить контакт');
          btn.addEvent('click', function(e){
            e.preventDefault();
            obj.addContact(btn.getProperty('id'));
          }.bind(btn));
        });
        results.getElements('a[class=addFriend]').each(function (btn, i) {
          btn.setProperty('title', 'Добавить в друзья');
          btn.addEvent('click', function(e){
            e.preventDefault();
            obj.addFriend(btn.getProperty('id'));
          }.bind(btn));
        });
        */
      }
    }).POST({
      'action' : 'ajaxSearchUser',
      'userSearch' : userMask
    });
  },
  addContactComplete: function() {
    this.getContacts();
  },
  getContactsComplete: function(data) {
    $('contacts').innerHTML = data;
    this.setContextMenuContacts();
  },

  setContextMenuContacts: function() {
    $('contacts').getElements('div.userFolder').each(function (user, i) {
      var userLink = user.getElement('a[class^=user]');
      var userData = JSON.decode(userLink.getProperty('title'));
      userLink.setProperty('title', '');
      new ContextMenuUser(
        'msg',
        userLink,
        $('contextMenuContacts').clone().injectAfter(user),
        userData,
        this
      );
    }.bind(this));
  },

  setContextMenuSearch: function() {
    $('results').getElements('div.userFolder').each(function (user, i) {
      var userLink = user.getElement('a.user');
      var userData = JSON.decode(userLink.getProperty('title'));
      userLink.setProperty('title', '');
      new ContextMenuUser(
        'msg',
        userLink,
        $('contextMenuSearch').clone().injectAfter(user),
        userData,
        this
      );
    }.bind(this));
  },

  getFriendsQueueComplete: function(data) {
    $('friendsQueue').innerHTML = data;
    this.setFriendsQueueBtns();
  },

  setFriendsQueueBtns: function() {
    var obj = this;
    var friendsQueue = $('friendsQueue');
    friendsQueue.getElements('a.addFriend').each(function (btn, i) {
      btn.setProperty('title', 'Принять предложение дружбы');
      btn.addEvent('click', function(e){
        e.preventDefault();
        obj.acceptFriend(btn.getProperty('id'));
      }.bind(btn));
    });
    friendsQueue.getElements('a.declineFriend').each(function (btn, i) {
      btn.setProperty('title', 'Отклонить предложение дружбы');
      btn.addEvent('click', function(e){
        e.preventDefault();
        obj.declineFriend(btn.getProperty('id'));
      }.bind(btn));
    });
  },

  deleteContact: function(contactId) {
    if (confirm('Вы действительно хотите удалить этот контакт?')) this.parent(contactId);
  },
  deleteContactComplete: function() {
    this.getContacts();
  },
  addFriend: function(contactId) {
    if (confirm('Вы действительно хотите добавить в друзья этот контакт?')) this.parent(contactId);
  },
  addFriendComplete: function() {
    this.getContacts();
  },
  deleteFriend: function(contactId) {
    if (confirm('Вы действительно хотите удалить из друзей этот контакт?')) this.parent(contactId);
  },
  acceptFriendComplete: function() {
    this.getFriendsQueue();
    this.getContacts();
  },
  declineFriend: function(contactId) {
    if (confirm('Вы действительно хотите отклонить предложение о дружбе?')) this.parent(contactId);
  },
  declineFriendComplete: function() {
    this.getFriendsQueue();
  },
  deleteFriendComplete: function() {
    this.getContacts();
  }
});
