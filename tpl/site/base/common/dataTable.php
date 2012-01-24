<? if ($d['items']) { ?>
  <table>
  <? foreach ($d['items'] as $k => $v) {
  	   if (!$d['titles'][$k] or !$v) continue; ?>
    <tr>
      <td><b><?= $d['titles'][$k] ?></b>:</td>
      <td><?
      if (is_array($v)) {
      	if ($v['v']) print $v['v'];
      	else print Tt::enumK($v, 'v');
      } else print $v; 
      ?></td>
    </tr>
  <? } ?>
  </table>
<? } ?>