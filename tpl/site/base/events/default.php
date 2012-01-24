<? if ($d['pagination']['pNums']) { ?><div class="pNums pNumsTop"><?= $d['pagination']['pNums'] ?><div class="end2"><!-- --></div></div><? } ?>

<?

$descr = array(
  'createDDItem' => 'Создание',
  'updateDDItem' => 'Изменение',
  'deleteDDItem' => 'Удаление',
  'activateDDItem' => 'Активация',
  'deactivateDDItem' => 'Дезактивация',
);

?>

<div class="items">
<? foreach ($d['items'] as $k => $v) { ?>
  <div class="item">
    <b><a href="<?= Tt::getUserPath($v['userId']) ?>" target="_blank">
      <?= $v['login'] ?></b></a> (<?= $v['dateCreate'] ?>):
    <b><?= $descr[$v['name']] ?></b><br />
    <b><a href="/<?= $v['data']['page']['name'].'/'.$v['data']['itemId'] ?>" target="_blank">
      <?= $v['data']['title'] ?></a></b><br />
    <small><?= Tt::enumDddd($v['data']['page']['pathData'], '<a href="$link">$title</a>', ' / ') ?></small>    
  </div>
<? } ?>
</div>