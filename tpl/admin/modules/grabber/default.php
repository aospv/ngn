<? Tt::tpl('admin/modules/grabber/header', $d) ?>

<? if ($d['items']) { ?>

<style>
.tools .clear {
width: 280px;
}
.tools .sm-delete {
margin: 3px 0px 0px 3px;
}
.loader {
background-position: 270px 7px;
}
#itemsTable tr {
background: #FFFFFF;
}
</style>

<table cellpadding="0" cellspacing="0" id="itemsTable" class="valign">
<thead>
<tr>
  <th>&nbsp;</th>
  <th>Последний шаг</th>
  <th>Тип</th>
  <th>Название</th>
  <th>&nbsp;</th>
  <th>Ссылка</th>
  <th><small>Осталось до следующей проверки</small></th>
  <th>Последнее получение</th>
  <th><small>Полученено записей в последний раз</small></th>
  <th>Последняя проверка</th>
  <th><small>Неудачных попыток</small></th>
</tr>
</thead>
<tbody>
<? foreach ($d['items'] as $v) {
  
  $n++;
  $v['linksCount'] = str_replace(0, '???', $v['linksCount']);

?>
<tr class="<?= 
($v['attempts'] ? 'errorBg ' : '').
($v['active'] ? '' : ' nonActive').
($v['unknownTotalCount'] ? ' unknownTotalCount' : '')
?>" id="<?= 'item_'.$v['id'].'_'.$v['oid'] ?>">
  <td class="tools loader">
    <div class="dragBox tooltip" title="Схватить и перетащить"></div>
    <a href="<?= Tt::getPath(3).'/edit?id='.$v['id'] ?>" class="iconBtn edit" title="Редактировать"><i></i></a>
    <a href="" class="iconBtn delete" title="Удалить"><i></i></a>
    <a class="iconBtn <?= $v['active'] ? 'activate' : 'deactivate' ?>" title="<?= $v['active'] ? LANG_HIDE : LANG_SHOW ?>"
      href="<?= Tt::getPath(3) ?>?a=<?= ($v['active'] ? 'deactivate' : 'activate') . '&id='.$v['id'] ?>"><i></i></a>
    <a href="" class="iconBtn test" title="Тестировать"><i></i></a>
    <a href="" class="iconBtn import" title="Импортировать новое"><i></i></a>
    
    <!-- Import All Interface -->
    <? if (!$v['saveLinksFinished']) { ?>
      <a href="" class="iconBtn refrash saveLinks" title="Импортировать весь канал. Сохранение ссылок"><i></i></a>
      <div class="iconBtnCaption iconBtnCaptionLinks tooltip" title="Добавлено ссылок <?= $v['itemsCount'].' из '.$v['linksCount'] ?>"><?= $v['itemsCount'].'/'.$v['linksCount'] ?></div>
      <div class="smIcons bordered"><a href="" class="sm-delete deleteLinks" title="Очистить полученные ссылки"><i></i></a></div>
    <? } elseif (!$v['importAllFinished']) { ?>
      <a href="" class="iconBtn refrash importAll" title="Импортировать весь канал"><i></i></a>
      <div class="iconBtnCaption tooltip" title="Импортировано записей <?= $v['importedCount'].' из '.$v['itemsCount'] ?>"><?= $v['importedCount'].'/'.$v['itemsCount'] ?></div>
      <div class="smIcons bordered"><a href="" class="sm-delete deleteAllImported" title="Удалить импортированые записи"><i></i></a></div>
    <? } ?>
    <!-- End Import All Interface -->
    
    <div class="clear"><!-- --></div>
  </td>
  <td><?= $v['lastStep'] ?></td>
  <td><small><i><?= $v['type']['title'] ?></i></small></td>
  <td class="title"><?= $v['data']['title'] ?></td>
  <td><small class="info">
    <?= $v['unknownTotalCount'] ? 'кол-во записей не определено' : '' ?>
    <?= $v['saveLinksFinished'] ? 'сохранение ссылок завершено' : '' ?>
  </small></td>
  <td><a href="<?= $v['data']['url'] ?>" target="_blank" class="tooltip" title="<?= $v['data']['url'] ?>"><small><?= Misc::cutUrl($v['data']['url'], 30) ?></small></a></td>
  <td class="nextGrab"><?= date('m/d/Y H:i:s', $v['dateLastGrab_tStamp']+$d['frequency']) ?></td>
  <td><small><?= datetimeStr($v['dateLastGrab_tStamp']) ?></small></td>
  <td><?= $v['lastGrabbed'] ?></td>
  <td><small><?= datetimeStr($v['dateLastCheck_tStamp']) ?></small></td>
  <td><?= $v['attempts'] ? $v['attempts'] : '&nbsp;' ?></td>
</tr>
<? } ?>
</tbody>
</table>

<div id="countdown"></div>

<?= SFLM::getJsTags('grabber') ?>
<script type="text/javascript">
window.addEvent('domready', function(){
  new Ngn.GrabberChannelsTable();
});
</script>

<? } else { ?>
  <p class="info"><i></i>Не создано ниодного канала</p>
<? } ?>