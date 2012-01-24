<?

if ($_GET['completeAction'] == 'new') {
  print Slice::html(
    'complete_'.$d['page']['id'],
    'Сообщение об успешной отправке',
    'Ваш вопрос был отправлено администратору'
  );
} else Tt::directTpl('site/base/dd/complete', $d);