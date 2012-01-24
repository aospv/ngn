<?

if (empty($d['params'][1])) {
  print "<ul>";
  foreach ($d['tags']['rub'] as $v) {
    print "<li><a href=".Tt::getPath(1).'/t2.rub.'.$v['id'].">".$v['title']."</a></li>";
  }
  print "</ul>";
} else {
  Tt::tpl('dd/css', $d);
  print Slice::html(
    'beforeDdItems_'.$d['listSlicesId'],
    'Блок перед записями «'.$d['page']['title'].
      (empty($d['listSlicesTitle']) ? '' : ' / '.$d['listSlicesTitle']).'»'
  );
  $oDdLayoutOutput = DdoSiteFactory::get($d['page'], 'siteItems');
  $oDdLayoutOutput->setItems($d['items']);
  $oDdLayoutOutput->canEdit = in_array('edit', $d['allowedActions']);
  $oDdLayoutOutput->premoder = !empty($d['settings']['premoder']);
  Err::noticeSwitch(true);
  if (empty($d['settings']['doNotShowItems'])) {
    if (!empty($d['items'])) {
      print $oDdLayoutOutput->els();
      print '<div class="clear"><!-- --></div>';
    } else {
      print empty($d['page']['settings']['doNotShowNoItems']) ? 'Нет записей' : '';
      /*
      print Slice::html('noItems_'.$d['page']['id'].
        (isset($d['tagsSelected'][0]) ?
          '_'.$d['tagsSelected'][0]['id'] : ''),
        isset($d['tagsSelected'][0]) ?
          'Нет записей ('.$d['tagsSelected'][0]['title'].')' : 'Нет записей',
        'Нет записей');
      */
    }
  }

}