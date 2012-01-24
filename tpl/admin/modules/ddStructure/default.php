<? Tt::tpl('admin/modules/ddStructure/header') ?>

<style>
.descr .smIcons a, .descr a.smIcons {
margin-right: 2px;
}
</style>

<? if ($d['items']) { ?>
<table cellpadding="0" cellspacing="0" id="itemsTable" class="valign structures">
<tr>
  <th>&nbsp;</th>
  <th>Название</th>
  <th>Имя</th>
  <th>&nbsp;</th>
  <th>Описание</th>
</tr>
<? foreach ($d['items'] as $k => $v) { ?>
  <tr>
    <td class="tools">
      <a class="iconBtn delete confirm" title="Удалить структуру"
        href="<?= Tt::getPath() ?>?a=delete&id=<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn edit" title="Редактировать структуру"
        href="<?= Tt::getPath() ?>?a=edit&id=<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn fields" title="Редактировать поля структуры"
        href="<?= Tt::getPath(1) ?>/ddField/<?= $v['name'] ?>"><i></i></a>
    </td>
    <td><?= $v['title'] ?></td>
    <td><i><?= $v['name'] ?></i></td>
    <td>
      <? if ($v['type'] == 'variant') { ?>
        <span class="smIcons sm-variant tooltip" title="Любая структура"><i></i></span>
      <? } elseif ($v['type'] == 'static') { ?>
        <span class="smIcons sm-static tooltip" title="Статическая структура"><i></i></span>
      <? } else { ?>
        <span class="smIcons sm-dynamic tooltip" title="Динамическая структура"><i></i></span>
      <? } ?>
      
      <? if ($v['locked']) { ?>
        <span class="smIcons sm-lock tooltip" title="Структура с ограниченным доступом"><i></i></span>
      <? } ?>
      <? if ($v['indx']) { ?>
        <span class="smIcons sm-index tooltip" title="Структура разрешена для индексации"><i></i></span>
      <? } ?>
      &nbsp;
    </td>
    <td>
      <div class="descr">
        <? if ($v['pages']) { ?>
          <? foreach ($v['pages'] as $p) { ?>
            <div>
              <a href="<?= Tt::getPath(1).'/pages/'.$p['pageId'].'/editContent' ?>" class="smIcons bordered sm-edit" title="<?= LANG_EDIT ?>"><i></i></a>
              <a href="<?= Tt::getPath(1).'/pages/'.$p['pageId'].'/editPage' ?>" class="smIcons bordered sm-settings" title="<?= LANG_SETTING ?>"><i></i></a>
              <a href="/<?= $p['path'] ?>" target="_blank" class="smIcons bordered sm-link" title="<?= LANG_SHOW ?>"><i></i></a>
              <?php /* Сделать тут выпадающую подсказку на которой был бы написан путь */ ?>
              <small class="tooltip" title="<?= Tt::enumDddd($p['pathData'], '$title', ' / ') ?>"><?= $p['title'] ?></small>
              <div class="clear"><!-- --></div>
            </div>
          <? } ?>
        <? } ?>
        <? if ($v['descr']) {  ?><small><?= $v['descr'] ?>&nbsp;</small><? } ?>
      </div>
    </td>
  </tr>
<? } ?>
</table>
<? } else { ?>
<p>Пока что не создано ниодной структуры</p>
<? } ?>