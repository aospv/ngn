<? Tt::tpl('admin/modules/subscribe/header', $d) ?>

<? if ($d['users']) { ?>
<? if ($d['pagination']['pNums']) { ?>
  <div class="pNums pNumsTop">
    <?= $d['pagination']['pNums'] ?>
    <div class="clear"><!-- --></div>
  </div>
<? } ?>
<table cellpadding="0" cellspacing="0" id="itemsTable">
<thead>
  <tr>
    <th></th>
    <th>Логин</th>
    <th>E-mail</th>
  </tr>
</thead>
<tbody>
<? foreach ($d['users'] as $v) { ?>
  <tr>
    <td class="tools">
      <a class="iconBtn delete confirm" title="<?= LANG_DELETE ?>"
        href="<?= Tt::getPath() ?>?a=deleteUser&userId=<?= $v['id'] ?>"><i></i></a>
    </td>
    <td><?= $v['login'] ?></td>
    <td><?= $v['email'] ?></td>
  </tr>
<? } ?>
</tbody>
</table>
<? if ($d['pagination']['pNums']) { ?>
  <div class="pNums pNumsBottom">
    <?= $d['pagination']['pNums'] ?>
    <div class="clear"><!-- --></div>
  </div>
<? } ?>
<? } else { ?>
  На лист рассылки не подписан ниодин пользователь
<? } ?>