<?

$event2cssClass = array(
  'updateItem' => 'edit',
  'createItem' => 'add',
  'deleteItem' => 'delete'
);

$oEAO = new EventsAdminOutput($d['items']);

?>

<div class="navSub iconsSet">
  <a href="<?= Tt::getPath() ?>?a=deleteAll" class="confirm delete"><i></i>Удалить все</a>
  <div class="clear"><!-- --></div>
</div>
<? if ($d['items']) { ?>
  <? if ($d['pagination']['pNums']) { ?>
    <div class="pNums pNumsTop">
      <?= $d['pagination']['pNums'] ?>
      <div class="clear"><!-- --></div>
    </div>
  <? } ?>
  <table cellpadding="0" cellspacing="0" id="itemsTable">
  <thead>
    <tr>
      <th>Время, дата</th>    
      <th>&nbsp;</th>    
      <th>Пользователь</th>    
      <th>Страница</th>    
    </tr>
  </thead>
  <tbody>
  <? foreach ($d['items'] as $k => $v) { // die2($v); ?>
    <tr>
      <td><small><?= datetimeStr($v['dateCreate_tStamp']) ?></small></td>
      <td class="iconsSet"><div class="<?= $event2cssClass[$v['name']] ?> tooltip" title="<?= $v['title'] ?>"><i></i></div></td>
      <td class="smIcons"><a href="<?= Tt::getPath(1).'/users?a=edit&id='.$v['data']['authorId'] ?>" class="sm-user"><i></i><?= $v['data']['authorLogin'] ?></a></td>
      <td class="smIcons bordered">
        <a href="<?= Tt::getPath(1).'pages/'.$v['data']['pageId'].'/editContent?a=edit&id='.$v['data']['id'] ?>" class="sm-edit tooltip" title="<?= LANG_EDIT ?>"><i></i></a>
        <a href="<?= Tt::getPath(0).$v['data']['pagePath'].'/'.$v['data']['id'] ?>" target="_blank" 
          class="sm-link noborder tooltip" title="<?= LANG_SHOW ?>">
          <i></i><?= $v['data']['title'] ?></a>
        <?php /*<small class="gray"><?= Tt::enumDddd($v['data']['pathData'], '$title', ' / ') ?></small>*/?>
      </td>
      <td></td>
    </tr>
  <? } ?>
  </tbody>
  </table>
  <? if ($d['pagination']['pNums']) { ?>
    <div class="pNums pNumsTop">
      <?= $d['pagination']['pNums'] ?>
      <div class="clear"><!-- --></div>
    </div>
  <? } ?>
<? } else { ?>
  <p class="info"><i></i><?= LANG_NO_ENTRIES ?></p>
<? } ?>
  