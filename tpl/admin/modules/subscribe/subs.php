<? Tt::tpl('admin/modules/subscribe/header', $d) ?>
<? if (!$d['items']) { ?>
  <p>Нет рассылок</p>
<? } else { ?>
  <table cellpadding="0" cellspacing="0" id="itemsTable" class="valign">
  <thead>
    <tr>
      <th>&nbsp;</th>
      <th>Время начала рассылки</th>
      <th>Время окончания рассылки</th>
      <th>Статус</th>
      <th>Возвраты</th>
      <th>Разослано</th>
      <th>Текст</th>
    </tr>
  </thead>
  <tbody>
  <? foreach ($d['items'] as $v) {
    $closed = !($v['subsEndDate_tStamp'] == 0);
    ?>
    <tr<?= $closed ? '' : ' class="notViewed"' ?>>
      <td class="tools">
        <a class="iconBtn delete confirm" title="Удалить"
          href="<?= Tt::getPath(4).'?a=deleteSubs&id='.$v['id'] ?>"><i></i></a>
        <a class="iconBtn stat" title="Статистика возвратов"
          href="<?= Tt::getPath(2) ?>/returns/<?= $v['listId'].'/'.$v['id'] ?>"><i></i></a>
        <? if (!$closed) { ?>
          <a class="iconBtn subscribe" title="Продолжить отправку" data-id="<?= $v['id'] ?>"
            href=""><i></i></a>
          <a class="iconBtn close confirm" title="Завершить рассылку"
            href="<?= Tt::getPath(4).'?a=closeSubs&id='.$v['id'] ?>"><i></i></a>
        <? } else { ?>
          <span class="iconBtn dummy"><i></i></span>
        <? } ?>
      </td>
      <td><?= datetimeStr($v['subsBeginDate_tStamp']) ?></td>
      <td><?= $v['subsEndDate_tStamp'] ? datetimeStr($v['subsEndDate_tStamp']) : '' ?></td>
      <td><?= $v['subsEndDate_tStamp'] == 0 ? 'не завершена' : 'завершена' ?></td>
      <td><?= $v['returnsCnt'].'/'.$v['totalCnt'].'='.(round($v['returnsCnt']/$v['totalCnt']*10000)/100).'%' ?></td>
      <td><?= $v['sentCnt'].'/'.$v['totalCnt'].'='.(round($v['sentCnt']/$v['totalCnt']*10000)/100).'%' ?></td>
      <td width="300"><?= Misc::cut($v['text'], 200) ?></td>
    </tr>
  <? } ?>
  </tbody>
  </table>

<script>
$('itemsTable').getElements('a[class~=subscribe]').each(function(el){
  el.addEvent('click', function(){
    if (!confirm('Вы точно уверены, что хотите продолжить отправку писем?'))
      return false;
    new Ngn.PartialJobParallel(
      Tt::getPath() + '?a=json_send',
      {
        loaderTitleStart: 'Отправка писем',
        loaderTitleComplete: 'Готово',
        onComplete: function(data) {
          window.location = Tt::getPath(2) + '/subs/' + Ngn.getParam(3);
        },
        requestParams: {
          subsId: el.get('data-id').toInt()
        }
      }
    ).start();
    return false;
  });
});
</script>

<? } ?>
