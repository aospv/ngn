<? Tt::tpl('admin/modules/subscribe/header') ?>

<? if ($d['items']) { ?>
<? foreach ($d['items'] as $v) { ?>
<table cellpadding="0" cellspacing="0" id="itemsTable">
<tbody>
  <tr>
    <td class="tools">
      <a class="iconBtn edit" title="<?= LANG_EDIT ?>"
        href="<?= Tt::getPath(2) ?>/edit/<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn delete confirm" title="<?= LANG_DELETE ?>"
        href="<?= Tt::getPath(2) ?>?a=delete&id=<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn <?= $v['active'] ? 'activate' : 'deactivate' ?>" title="<?= $v['active'] ? LANG_HIDE : LANG_SHOW ?>"
        href="<?= Tt::getPath(3) ?>?a=<?= ($v['active'] ? 'deactivate' : 'activate') . '&id='.$v['id'] ?>"><i></i></a>
      <a class="iconBtn subscribe" title="Разослать"
        href="<?= Tt::getPath(2) ?>/send/<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn email" title="Подписанные ящики"
        href="<?= Tt::getPath(2) ?>/emails/<?= $v['id'] ?>"><i></i></a>
      <? if ($v['useUsers']) { ?>
        <a class="iconBtn users" title="Подписанные пользователи"
          href="<?= Tt::getPath(2) ?>/users/<?= $v['id'] ?>"><i></i></a>
      <? } else { ?>
        <a class="iconBtn dummy"><i></i></a>
      <? } ?>
      <a class="iconBtn import" title="Добавить ящики"
        href="<?= Tt::getPath(2) ?>/import/<?= $v['id'] ?>"><i></i></a>
      <a class="iconBtn list" title="Рассылки"
        href="<?= Tt::getPath(2).'/subs/'.$v['id'] ?>"><i></i></a>
    </td>
    <td class="iconsSet"><a href="<?= Tt::getPath(2).'/subs/'.$v['id'] ?>" class="list" title="Рассылки"><i></i><?= $v['title'] ?></a></td>
  </tr>
</tbody>
</table>
<? } ?>
<? } else { ?>
  Не создано ниодной листа рассылок
<? } ?>