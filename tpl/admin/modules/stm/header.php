<?

$links[] = array(
  'title' => 'Темы',
  'class' => 'list',
  'link' => Tt::getPath(2),
);
$links[] = array(
  'title' => 'Изменить тему',
  'class' => 'edit dialogForm',
  'link' => Tt::getPath(2).'/json_changeTheme',
);
$links[] = array(
  'title' => 'Создать тему',
  'class' => 'add dialogForm',
  'link' => Tt::getPath(2).'/json_themeNewStep1',
);
if ($d['action'] == 'editTheme') {
  $links[] = array(
    'title' => 'Предпросмотр темы',
    'class' => 'preview',
    'target' => '_blank',
    'link' => Tt::getPath(0).'/?theme[location]='.$d['params'][3].'&theme[design]='.$d['params'][5].'&theme[n]='.$d['params'][6],
  );
}
$links[] = array(
  'separator' => true
);
$links[] = array(
  'title' => 'Список меню',
  'class' => 'list',
  'link' => Tt::getPath(2).'/menuList',
);
$links[] = array(
  'title' => 'Создать меню',
  'class' => 'add',
  'link' => Tt::getPath(2).'/menuNewStep1',
);

Tt::tpl('admin/common/module-header', array('links' => $links));
