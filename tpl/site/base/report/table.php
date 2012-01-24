<?
/*
 * Пример массива $d['items']:
 * array(
 *   array(
 *     'data' => array(
 *       'title' => 'Отчет об успеваемости',
 *       'id' => 'ID баннера',
 *       'color' => '#FF0000'
 *     )
 *     'report' => array(3, 6, 8, 9, 4, 5)
 *   ),
 *   ...
 * )
 */
?>
<table cellspacing="0" cellpadding="0">
<tr>
  <th><?= $d['xyTitles'] ?></th>
<? foreach ($d['barX'] as $k => $v) { ?>
  <th><?= $v ?></th>
<? } ?>
</tr>
<? foreach ($d['items'] as $k => $v) { ?>
<tr>
  <td>
  <b style="color: <?= $v['data']['color'] ?>">&bull;</b>
  <a href="/common/click?id=<?= $v['data']['id'] ?>" target="_blank">
  <?= $v['data']['title'] ?></a></td>
<? foreach ($v['report'] as $v2) { ?>
  <td><?= $v2 ?></td>
<? } ?>
</tr>
<? } ?>
</table>