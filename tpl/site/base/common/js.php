if (!Ngn) var Ngn = {};
Ngn.isGod = <?= Misc::isGod() ? 'true' : 'false' ?>;
Ngn.isAdmin = <?= Misc::isAdmin() ? 'true' : 'false' ?>;

window.addEvent('domready', function() {

  // Выравнивание высоты tile-записей
  new Ngn.site.DdItems.EquailSizes('.ddil_tile .ddItems .item');

  var ddItems = document.getElement('.mainBody .ddItems');
  <? if (($userId = Auth::get('id')) and $d['isItemsController']) { ?>
    if (ddItems) new Ngn.site.DdItems(ddItems.getElements('.item'), {
      curUserId: <?= $userId ?>,
      isAdmin: <?= (int)Misc::isAdmin() ?>,
      editPath: <?= Arr::jsValue(empty($d['editPath']) ? null : $d['editPath']) ?>,
      sortables: <?= $d['page']['settings']['order'] == 'oid' ? 'true' : 'false' ?>
    });
  <? } ?>

  <? if (SiteLayout::topEnabled()) { ?>
  Ngn.site.top.auth.init();
  <? } ?>

  <? if (Misc::isAdmin()) { ?>
  new Ngn.slice.Layout();
  Ngn.pageBlocks = new Ngn.PageBlocksEdit({
     wrapperSelector: '.pageLayout',
     controllerPath: '/admin/pageBlocks/' + Ngn.site.page.id,
     colBodySelector: '.pageBlocks',
     disableDeleteBtn: true
  });
  <? } ?>
  
  // Табы
  var eTabs = $('tabs');
  if (eTabs) {
    var tabs = new Ngn.Tabs('tabs', {selector: 'h2[class=tab]'});
    var ul = document.getElement('.tab-menu');
    var submenu = $('submenu');
    submenu.empty();
    ul.inject(submenu);
    new Element('div', {'class':'clear'}).inject(ul);
    var anch = window.location.href.split('#')[1];
    if (anch) {
      if (anch.match(/cmt\d+|msgs/))
        // Конкретный коммент. Перключаем на вкладку комментов
        tabs.selectByName('bmt_comments'); 
      else tabs.selectByName(anch);
    }
  }
  
  // Всплывающее окно авторизации
  $$('a.btnAuth').each(function(el){
    el.addEvent('click', function(e) {
      e.preventDefault();
      new Ngn.Dialog.Auth({
        selectedTab: 0
      });
    });
  });
  

  <?php /*
  
  // Всплывающие подсказки
  //new Tips('.tools a,.tooltips a,.tooltip');
  
  // Кнопки сохранения
  // new Ngn.SubmitButtons();
  
  // Автокомплитеры
  //Ngn.Autocompleters.init();
  document.getElements('input[id^=ac-]').each(function(eInput) {
    new Ngn.Autocompleter(eInput, {
      minLength: 2
    });
  });

  <? if ($d['settings']['showRating']) { ?>
  // Рейтинги
  <? if ($d['action'] == 'list' or $d['action'] == 'blocks') { ?>
  new Ngn.ItemsRating({
    isMinus: <?= Config::getVarVar('rating', 'isMinus') ? 'true' : 'false' ?>,
    maxStars: <?= RatingSettings::getMaxStars() ?>,
    strName: '<?= $d['page']['strName'] ?>',
    allowVotingLog: <?= Config::getVarVar('rating', 'allowVotingLogForAll') ? 'true' : 'false' ?>
  });
  <? } elseif ($d['action'] == 'showItem') { ?>
  new Ngn.ItemRating($('ddRating<?= $d['item']['id'] ?>'), {
    isMinus: <?= Config::getVarVar('rating', 'isMinus') ? 'true' : 'false' ?>,
    maxStars: <?= RatingSettings::getMaxStars() ?>,
    strName: '<?= $d['page']['strName'] ?>',
    allowVotingLog: <?= Config::getVarVar('rating', 'allowVotingLogForAll') ? 'true' : 'false' ?>
  });
  <? } ?>
  <? } ?>
  
  <? if ($d['action'] == 'list') { ?>
  Ngn.videoItems();
  <? } ?> 

  <? if (($key = Config::getVarVar('google', 'mapKey', true))) { ?>
  // Замена адресов google-картами
  $$('.t_geoAddress').each(function(el){
    new Ngn.Atlas(el, {
      key: '<?= $key ?>'
    });
  });
  <? } ?>
  
  <? if ($d['calendar'] and $d['action'] == 'list') { ?>
  // Календарь фильтров дат
  var eCalendar = $('ddCalendar');
  if (eCalendar) new Ngn.DdCalendar(eCalendar);
  <? } ?>
  
  */?>
  
  var btnCreate = $('btnCreate');
  <? if ($d['authToCreate']) { ?>
  // Окно авторизации при нажатие на кнопку "Новая запись"
  if (btnCreate) {
    btnCreate.addEvent('click', function(e){
      e.preventDefault();
      new Ngn.Dialog.Auth({
        completeUrl: btnCreate.get('href')
      });
    });
  }
  <? } else { ?>
  if (btnCreate) {
    btnCreate.addEvent('click', function(e){
      e.preventDefault();
      new Ngn.Dialog.RequestForm({
        title: false,
        url: this.get('href').replace('a=new', 'a=json_new'),
        onSubmitSuccess: function() {
          window.location.reload(true);
        }
      });
    });
  }
  <? } ?>
  
  // Формы
  var eForm = document.getElement('.apeform form');
  if (eForm) new Ngn.Form(eForm);
  
  // Лайтбокс
  Ngn.lightbox.add($$('a.lightbox,a.iiLink]'));
  
});

Ngn.regNamespace('Ngn.site.top.briefcase.btns', true);

<?
if (($page = DbModelCore::get('pages', 'myProfile', 'controller')) !== false) {
  $exists = DbModelCore::get('dd_i_profileSimple', $userId, 'userId');
?>
Ngn.site.top.briefcase.btns.unshift(['profile', '<?= $exists ? 'Редактировать профиль' : '<b>Заполните профиль</b>' ?>', function() {
  new Ngn.Dialog.RequestForm({
    url: '/<?= Tt::getControllerPath('myProfile').'/'.$userId ?>?a=json_<?= $exists ? 'edit' : 'new' ?>',
    onSubmitSuccess: function() {
      window.location = '<?= Tt::getControllerPath('userData').'/'.Auth::get('id') ?>';
    }
  });
}]);
<? } ?>

<? if (Config::getVarVar('role', 'enable')) { ?>
Ngn.site.top.briefcase.btns.push(['role', 'Изменить тип профиля', function() {
  new Ngn.Dialog.RequestForm({
    url: '/c/userRole',
    title: false
  });
}]);
<? } ?>

<? if (Tt::getControllerPath('userReg', true)) { ?>
Ngn.site.top.briefcase.btns.push(['settings', 'Параметра аккаунта', function() {
  window.location = '<?= Tt::getControllerPath('userReg').'/editLogin' ?>';
}]);
<? } ?>

<? PageModuleCore::inlineJs($d) ?>

<? if ($d['oController']->userGroup) Tt::tpl('js/userGroup', $d) ?>