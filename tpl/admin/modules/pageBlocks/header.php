<?

if ($d['page']) {
  /*
  $links[] = array(
    'title' => 'Блоки',
    'class' => 'list',
    'link' => Tt::getPath(3),
  );
  */
  if ($d['god']) {
    if ($d['globalBlocksAdded']) {
      $links[] = array(
        'title' => 'Убрать дубликаты глобальных блоков',
        'class' => 'delete',
        'link' => Tt::getPath(3).'/deleteGlobalBlocksDuplicates',
      );
    } else {
      $links[] = array(
        'title' => 'Добавить дубликаты глобальных блоков',
        'class' => 'add',
        'link' => Tt::getPath(3).'/createGlobalBlocksDuplicates',
      );
    }
  }

  Tt::tpl('admin/common/module-header', array('links' => $links));
}
