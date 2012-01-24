Ngn.Updater = new Class( {
  initialize : function(baseUrl, currentBuildN) {
    this.baseUrl = baseUrl;
    this.currentBuildN = currentBuildN;
  },
  check : function() {
    new Request( {
      url: Ngn.getPath(0) + '/c2/updater?a=ajax_checkForNewBuild',
      onSuccess: function(r) {
        if (!numerical(r)) {
          alert('Ошибка при проверке наличия новой сборки: ' + r);
          return;
        }
        if (r != 0) {
          if (r <= this.currentBuildN) {
            alert('Имеющаяся сборка №' + r + ' уже установлена');
            return;
          }
          if (confirm('Обнаружена новая сборка №' + r + '. Установить?')) {
            new Request( {
              url: Ngn.getPath(0) + '/c2/updater?a=ajax_installNewBuild',
              onSuccess: function(r) {
                if (r == 'success') {
                  alert('Новая сборка успешно установлена');
                  window.location.reload(true);
                } else {
                  alert('Установка не удалась');
                }
              }
            }).send();
          }
        } else {
          alert('Новых сборок не обнаружено');
        }
      }.bind(this)
    }).send();
  }
});
