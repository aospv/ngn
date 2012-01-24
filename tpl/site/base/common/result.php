<?

if ($d['success'])
  Tt::tpl(
    'common/success',
    'Обновление E-maila прошло успешно');
elseif ($d['errors']) Tt::tpl('common/errors', $d['errors']); 
  
?>