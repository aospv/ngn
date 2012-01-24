<?

if ($_REQUEST['completeAction'] == 'new') {
  print Slice::create('complete_', $d['page']['id'], 'Сообщение об успешной отправке');
} else {
  Tt::directTpl('site/base/dd/complete', $d);
}