<? if (!$d['elements']) return; ?>
<table border="1" cellpadding="4" cellspacing="0">
<? foreach ($d['elements'] as $k => $v) { if (!$v) continue; ?>
  <tr>
    <td valign="top"><?= $d['fields'][$k]['title'] ?></td>
    <td valign="top"><?= $v ?></td>
  </tr>
<? } ?>
</table>
<?

// реализовать более гибко
//Tt::tpl('common/ddEventSubscript', $d) ?>