<? Tt::tpl('admin/modules/privileges/header', $d) ?>

<div class="priviliges tooltips">
<? if ($d['items']) { ?>
  <? foreach ($d['items'] as $pageId => $v) { ?>
    <div class="item">
      <div class="pageLink">
        <b><a class="smIcons sm-link" href="<?= Tt::getPath(2).'/'.$pageId.'/pagePrivileges' ?>" title="Редактировать привилегии для этого раздела"><i></i><?= $v['pageTitle'] ?></a></b>
        <div class="smIcons bordered">
          <!-- <a href="<?= Tt::getPath(1).'/pages/'.$pageId.'?a=editPageRedirect' ?>" class="sm-editPage" title="<?= LANG_PAGE_PROPERTIES ?>"><i></i></a> -->
          <a href="<?= Tt::getPath(2).'/'.$pageId.'/delete' ?>" class="sm-delete confirm" title="<?= LANG_DELETE ?>"><i></i></a>
        </div>
        <div class="clear"><!-- --></div>
      </div>
      <? foreach ($v['users'] as $userId => $user) { ?>
        <div class="smIcons user">
          <a href="<?= Tt::getPath(2) ?>/0/userPrivileges?userId=<?= $userId ?>" title="Редактировать привилегии для этого пользователя"><i></i><?= $user['login'] ?></a>
          <small class="privs">
          <? foreach ($user['types'] as $k3 => $v3) { ?>
            <?= $v3 ?>&nbsp;
          <? } ?>
          </small>
        </div>
      <? } ?>
      <div class="clear"><!-- --></div>
    </div>
  <? } ?>
<? } else { ?>
  <p>Привилегии отсутствуют</p>
<? } ?>  
</div>