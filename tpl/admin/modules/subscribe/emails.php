<? Tt::tpl('admin/modules/subscribe/header', $d) ?>

<? if ($d['emails']) { ?>
<? Tt::tpl('admin/common/pnums', $d) ?>
<? foreach ($d['emails'] as $v) { ?>
<table cellpadding="0" cellspacing="0" id="itemsTable">
<tbody>
  <tr>
    <td class="tools">
      <a class="iconBtn delete confirm" title="<?= LANG_DELETE ?>"
        href="<?= Tt::getPath(4) ?>?a=deleteEmail&email=<?= $v['email'] ?>"><i></i></a>
    </td>
    <td><?= $v['email'] ?></td>
  </tr>
</tbody>
</table>
<? } ?>
<? Tt::tpl('admin/common/pnums', $d) ?>
<? } else { ?>
  В листе рассылки нет ниодного ящика
<? } ?>