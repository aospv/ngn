<?

if (!($text = Slice::html(
  'actionNotAllowed_'.$d['page']['id'].'_'.$d['action'],
  'Запрет на выполнение экшена «'.$d['action'].'» раздела «'.$d['page']['title'].'»'
))) print 'Действие запрещено.';