<? Tt::tpl('admin/modules/pages/header', $d) ?>

<form action="<?= Tt::getPath() ?>" method="POST" id="pageForm">
  <input type="hidden" name="action" value="<?= $d['postAction'] ?>" />
  <? if ($d['ep']) { ?><input type="hidden" name="id" value="<?= $d['ep']['id'] ?>" /><? } ?>
  
  <div class="col" style="width:350px">
    <p>
      <b><?= LANG_TITLE ?>*:</b><br />
      <input type="text" name="page[title]" id="pageTitle" value="<?= $d['ep']['title'] ?>" class="fldLarge" style="width:100%;" />
    </p>
    <div class="iconsSet">
      <label for="radioFolder" class="folder"><input type="radio" name="page[folder]" id="radioFolder" class="rad" value="1"<?= $d['ep']['folder'] ? ' checked' : '' ?> /><i></i><div class="tit"><?= LANG_DIRECTORY ?></div></label>
      <label for="radioNonfolder" class="page"><input type="radio" name="page[folder]" id="radioNonfolder" class="rad" value="0"<?= !$d['ep']['folder'] ? ' checked' : '' ?> /><i></i><div class="tit"><?= LANG_PAGE ?></div></label>
    </div>
    <div class="clear"><!-- --></div>
    <? if ($d['action'] != 'newPageInstall') { ?>
    <div class="iconsSet" style="margin-top:10px;">
      <label for="checkboxRedirect" class="link"><input type="checkbox" id="checkboxRedirect" class="rad" value="redirect" <?= $d['ep']['link'] ? 'checked' : '' ?> /><i></i><div class="tit"><?= LANG_LINK ?></div></label>
      <div class="clear"><!-- --></div>
    </div>
    <? } ?>
    <p class="iconsSet">
      <label for="home" class="home">
        <input type="checkbox" name="page[home]" id="home" class="rad" 
          value="1"<?= $d['ep']['home'] ? ' checked' : '' ?> />
          <i></i><div class="tit">сделать домашняя страница</div></label>
      <div class="clear"><!-- --></div>
    </p>
    <p><small><?= LANG_DIFFERENCE_BETTWEEN_PAGE_FOLDER ?></small></p>
    <div style="margin-top:15px">
      <div style="float:left;"><input type="submit" value="<?= $d['action'] == 'newPage' ? LANG_CREATE_SECTION : LANG_SAVE ?>" style="width:90px;height:30px;" /></div>
      <div class="saveAndReturn" style="width:240px;">
        <label for="saveAndReturn"><input type="checkbox" name="saveAndReturn" value="1" id="saveAndReturn" /> <small>возвратиться на редактирование параметров раздела после сохранения</small></label>
      </div>
      <div class="clear"><!-- --></div>
    </div>
  </div>  
  
  <div class="col">
    <p>
      <b><?= LANG_NAME ?>*:</b><br />
      <input type="text" name="page[name]" id="pageName" value="<?= $d['ep']['name'] ?>" style="width:200px;" /><br />
      <p><small><?= LANG_PAGE_NAME_FORMED ?></small></p>
    </p>
    <p id="redirectBlock" style="display:none">
      <b><?= LANG_LINK ?>:</b><br />
      <input type="text" name="page[link]" id="link" value="<?= $d['ep']['link'] ?>" style="width:200px;" /><br />
    </p>
    <div id="advSet">
      <? if ($d['action'] == 'newModulePage') { ?>
      <p>
        <b><?= LANG_MODULE ?>:</b>
        <?= Html::select('page[module]', $d['modules']) ?>
      </p>
      <? } else { ?>
        <? if (Misc::isGod()) { ?>
        <?php /*
        <p>
          <label for="slave"><input type="checkbox" name="page[slave]" id="slave" value="1" <?= $d['ep']['slave'] ? 'checked' : '' ?> /> - <b>slave</b></label>
        </p>
        */?>
        <p>
          <b>Экшн по умолчанию:</b>
          <?= Html::select(
            'defaultAction',
            DefaultAction::options(),
            isset($d['ep']['settings']['defaultAction']) ? $d['ep']['settings']['defaultAction'] : ''
          ) ?>
        </p>
        <p>
          <b>Модуль:</b>
          <input type="text" name="page[module]" value="<?= $d['ep']['module'] ?>" />
        </p>
        <? } ?>
      <p>
        <b><?= LANG_CONTROLLER ?>:</b>
        <?= $d['ep']['controller'] ? ' <small class="gray">('.$d['ep']['controller'].')</small>' : '' ?><br />
        <?= Html::select('page[controller]', $d['controllers'], $d['ep']['controller'], array('tagId' => 'controller')) ?>
      </p>
      <div id="pageControllerSettingsBlock" style="min-height:20px;">
        <h3><?= LANG_CONTROLLER_OPTIONS ?>:</h3>
        <div id="pageControllerSettings" class="apeform">
          <?= $d['controllerRquiredFields'] ?>
        </div>
      </div>
      <? } ?>
      
      <? if ($d['requiredEmptySettings']) { ?>
      <div class="error"><i></i>
        <small>
          Вы должны заполнить следующие из обязательных параметров этого модуля:
          <ul>
            <? foreach ($d['requiredEmptySettings'] as $v) { ?>
              <li><b><?= $v['title'] ?></b></li>
            <? } ?>
          </ul>
        </small>
      </div>
    <? } ?>
    </div>
  </div>
  <div class="clear"><!-- --></div>
</form>
    
<script type="text/javascript">
var pageNameEdited = false;
var pageNameExists = $('pageName').get('value') ? true : false;
var translatePageName = function(){
  if (pageNameExists || pageNameEdited) return;
  $('pageName').set('value', translate(trim(this.get('value'))));
  $('pageName').set('styles', {
    'border-color' : '#EFC400',
    'background-color' : '#FFF9DF'
  });
};
$('pageTitle').addEvent('keyup', translatePageName);
$('pageTitle').addEvent('click', translatePageName);
$('pageName').addEvent('keyup', function(e){
  pageNameEdited = true;
});

var setRedirectBlock = function() {
  if ($('checkboxRedirect').get('checked')) {
    $('redirectBlock').setStyle('display', 'block');
    $('advSet').setStyle('display', 'none');
  } else {
    $('redirectBlock').setStyle('display', 'none');
    $('advSet').setStyle('display', 'block');
  }
}
$('checkboxRedirect').addEvent('click', function(e){
  setRedirectBlock();
});
setRedirectBlock();

window.addEvent('domready',function() {


  <? if (!Misc::isGod()) { ?>
  // Дезактивируем определенные поля
  $('controller').set('disabled', true);
  $('pageControllerSettings').getElements('input[type!=hidden],select,textarea').each(function(el){
    el.set('disabled', true);
  });
  <? } ?>
  
  // --------------------------

  var pageForm = $('pageForm');
  pageForm.removeEvents('submit');
  pageForm.addEvent('submit', function(e){
    if ($('checkboxRedirect').get('checked')) {
      if (!$('link').get('value')) {
        alert('<?= LANG_FILL_LINK ?>');
        $('link').focus();
        e.preventDefault();
        return;
      }
    } else {
      $('link').set('value', '');
    }
  });

  var eController = $('controller');
  var ePageControllerSettings = $('pageControllerSettings');
  var ePageControllerSettingsBlock = $('pageControllerSettingsBlock');

  var loadSettingsFields = function() {
    // Проверяем существуют ли для выбранного контроллера обязательные параметры, если модуль выбран
    var controller = eController.get('value');
    if (controller) {
      ePageControllerSettingsBlock.setStyle('display', 'block');
      ePageControllerSettings.set('html', '');
      ePageControllerSettings.addClass('loader');
      new Request({
        method: 'get',
        url: window.location.pathname,
        data: {
          action: 'ajax_getControllerRequiredFields',
          controller: controller
        },
        onComplete: function(html) {
          ePageControllerSettings.removeClass('loader');
          if (!html) {
            ePageControllerSettingsBlock.setStyle('display', 'none');
            return;
          }
          ePageControllerSettings.set('html', html);
          extendDDFormInterface();
        }
      }).send();
      //disableSubmits.run(null, pageForm);
    }
  }

  var extendDDFormInterface = function() {
    //console.debug(document.getElement('input[name=strName]'));
  }
  
  //ePageControllerSettingsBlock.setStyle('display', 'none');
  
  if (eController) eController.addEvent('change', loadSettingsFields);

  //loadSettingsFields();
  
});
</script>
