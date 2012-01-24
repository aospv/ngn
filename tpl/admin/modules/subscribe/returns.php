<? Tt::tpl('admin/modules/subscribe/header', $d) ?>
<? if ($d['items']) { ?>
  <p>Всего: <?= count($d['items']) ?></p>
  <? if ($d['pagination']['pNums']) { ?>
    <div class="pNums pNumsTop">
      <?= $d['pagination']['pNums'] ?>
      <div class="clear"><!-- --></div>
    </div>
  <? } ?>
  <table cellpadding="0" cellspacing="0" id="itemsTable">
  <thead>
    <tr>
      <th>E-mail</th>
      <th>Тип</th>
      <th>Время возврата</th>
    </tr>
  </thead>
  <tbody>
  <? foreach ($d['items'] as $v) { ?>
    <tr>
      <td><?= $v['email'] ?></td>
      <td class="tools">
        <a class="iconBtn <?= $v['type'] == 'users' ? 'users' : 'email' ?>" title="<?= $v['type'] == 'users' ? 'Пользователь' : 'Ящик' ?>"><i></i></a>
      </td>
      <td><?= date('d.m.Y H:i:s', $v['returnDate_tStamp']) ?></td>
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
  <p>Возвратов нет</p>
<? } ?>