<? Tt::tpl('admin/modules/subscribe/header', $d) ?>

<? if ($d['unfinishedSubs']) { ?>
  <div class="error"><b>Внимание!</b> В листе «<b><?= $d['list']['title'] ?></b>» имеются <b><a href="<?= Tt::getPath(2).'/subs/'.$d['params'][3] ?>">незавершенные рассылки</a></b>.</div>
  <br />
<? } ?>

<div class="info">
  <b>Внимание!</b> При нажатии на кнопку произойдёт создание новой рассылки и 
  будет начат процесс отправки писем на все подписаные ящики.
</div>

<form action="<?= Tt::getPath(2).'/makeSend/'.$d['params'][3] ?>" method="post">
  <input type="button" value="Создать новую рассылку и отправить письма" style="width:300;height:30px;" id="btnSend" />
</form>

<script>
$('btnSend').addEvent('click', function(){
  if (!confirm('Вы уверены, что хотите разослать рассылку?')) return;
  new Ngn.PartialJobParallel(
    Tt::getPath() + '?a=json_send',
    {
      loaderTitleStart: 'Отправка писем',
      loaderTitleComplete: 'Готово',
      onComplete: function() {
        window.location = Tt::getPath(2) + '/subs/' + Ngn.getParam(3);
      }
    }
  ).start();
});
</script>
