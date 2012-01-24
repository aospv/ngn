<? if ($d['page']['controller'] == 'item') { ?>
  <p class="info">Сохранено успешно.</p>
  <ul>
    <li><a href="<?= Tt::getPath() ?>">« Вернуться</a></li>
  </ul>
<? } elseif ($d['page']['controller'] == 'profile') { ?>
  <p>Изменение информации вашего профиля прошло успешно.</p>
  <p>Вы можете сделать следующее</p>
  <ul>
    <li><a href="<?= Tt::getPath() ?>?a=edit">Продолжить редактирование</a></li>
    <li><a href="<?= Tt::getControllerPath('userData').'/'.Auth::get('id').'/'.$d['page']['strName'] ?>">Посмотреть на него</a></li>
  </ul>
<? } else { ?>
  <? if ($_GET['completeAction'] == 'deleteFile') { ?>
    <p>Файл записи <b><?= $d['item']['title'] ?></b> был успешно удален
  <? } else { ?>
    <p><?= ucfirst($d['page']['settings']['itemTitle']) ?> <b><?= $d['item']['title'] ?></b>
    <?
    
    if ($_GET['completeAction'] == 'edit')
      print NgnMorph::gender($d['page']['settings']['itemTitle'], array(
        'изменён', 'изменена', 'изменено'
      ));
    elseif ($_GET['completeAction'] == 'new')
      print NgnMorph::gender($d['page']['settings']['itemTitle'], array(
        'создан', 'создана', 'создано'
      ));
    elseif ($_GET['completeAction'] == 'publish')
      print NgnMorph::gender($d['page']['settings']['itemTitle'], array(
        'опубликован', 'опубликована', 'опубликовано'
      ));
    elseif ($_GET['completeAction'] == 'delete')
      print NgnMorph::gender($d['page']['settings']['itemTitle'], array(
        'удалён', 'удалена', 'удалено'
      ));
    ?> 
      успешно.</p>
    <? if ($d['settings']['premoder'] and Tt::getControllerPath('userData', true)) { ?>
      <p>Она появится на сайте после проверки модератором.</p>
      <? if ($d['moders']) { ?>
        <p class="dgray">Модераторы раздела:
          <?= Tt::enumDddd($d['moders'], '<a href="`.Tt::getUserPath($id).`">$login</a>') ?></p>
      <? } ?>
    <? } ?>
  <? } ?>
  <ul>
    <li><a href="<?= Tt::getPath() ?>">« Вернуться</a></li>
    <? if ($_REQUEST['completeAction'] != 'delete') {
    $t = NgnMorph::cast($d['page']['settings']['itemTitle'], array('ЕД', 'ВН'));
    ?>
      <li><a href="<?= Tt::getPath(1).'/'.$d['item']['id'] ?>">Посмотреть <?= $t ?></a></li>
      <li><a href="<?= Tt::getPath(1).'/'.$d['item']['id'].'?a=edit' ?>">Редактировать <?= $t ?></a></li>
    <? } ?>
  </ul>
  <? if ($d['settings']['premoder'] and $_REQUEST['completeAction'] == 'new') { ?>
  <div class="info">
    <p>Этот раздел является премодерируемым.
    Это значит, что ваша запись будет добавлена на сайт после проверки.</p>
    
    <? if (($path = Tt::getControllerPath('notify', true)) and
    !in_array('items_ownChange', Notify_SubscribeTypes::getUserTypes(Auth::get('id')))) { ?>
      <p>Если вы хотите быть в курсе, когда это произойдёт, подпишитесь на её обновления.</p>
      <p>Подписано успешно. <a href="<?= $path ?>">Управление уведомлениями</a></p>
      <a href="" class="btn btn2" id="subscribeOwnItemsChange"><span>Подписаться на обновления</span></a>
    <script type="text/javascript">
    var eSubsBtn = $('subscribeOwnItemsChange');
    //$('content').load('./?a=ajax_subscribeOwnItemsAndSetAllMethodsOn');
    eSubsBtn.addEvent('click', function(){
      eSubsBtn.load('<?= $path ?>?a=ajax_subscribeOwnItemsAndSetAllMethodsOn', {
        onLoad: function() {
          alert('!!!');
        }
      });
    });
    /*
    eSubsBtn.addEvent('click', function(){
      new Request({
        url: '<?= $path ?>',
        onComplete: function() {
          new Element('p', {html: 'Подписано успешно. <a href="<?= $path ?>">Управление уведомлениями</a>'}).inject(eSubsBtn, 'after');
          eSubsBtn.dispose();
        }.bind(this)
      }).GET({
        'action': 'ajax_subscribeOwnItemsAndSetAllMethodsOn',
      });
      return false;
    });
    */
    </script>
    <div class="clear"><!-- --></div>
    <? } ?>
    
  </div>
  <? } ?>
<? } ?>