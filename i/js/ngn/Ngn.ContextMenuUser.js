Ngn.ContextMenuUser = new Class({
  Extends: Ngn.ContextMenu,

  /**
   * Constructor
   *
   * @param   DOMObject       Кнопка
   * @param   DOMObject       Пользователь
   * @param   DOMObject       Контекстное меню
   * @param   СontactsLayout  Объект работы с контакт-листом, завязанный на DOM
   */
  initialize: function(url, btn, contextMenu, userData, contactsLayout) {
    this.parent(btn, contextMenu);
    this.url = url;
    this.userData = userData;
    this.contactsLayout = contactsLayout;
    this.id = btn.getProperty('id');

    if ($defined(this.userData)) {
      this.contextBtns.each(function (btn, i) {
        if ($defined(this.userData.waitFriend) && btn.className == 'waitFriend') {
          btn.setStyle('display', 'block');
        } else {
          if ($defined(this.userData.friend) &&
              !$defined(this.userData.waitFriend) &&
              btn.className == 'deleteFriend') {
            btn.setStyle('display', 'block');
          } else if (!$defined(this.userData.friend) &&
                    !$defined(this.userData.waitFriend) &&
                    btn.className == 'addFriend') {
            btn.setStyle('display', 'block');
          }
        }
      }.bind(this));
    }
  },

  click_deleteContact: function() {
    this.contactsLayout.deleteContact(this.id);
  },

  click_deleteFriend: function() {
    this.contactsLayout.deleteFriend(this.id);
  },

  click_sendMsg: function() {
    window.location = this.url + '?toUserId=' + this.id;
  },

  click_history: function() {
    wopen_center(this.url + '?action=history&toUserId=' + this.id, 'history', 500, 600);
  },

  click_portrait: function() {
    wopen_portret('/user/' + this.id + '/', 'portrait');
  },

  click_addFriend: function() {
    this.contactsLayout.addFriend(this.id);
  },

  click_addContact: function() {
    this.contactsLayout.addContact(this.id);
  }

});
