<?php

class UserMenu {
  
  static public function get(DbModelUsers $user, $curPageId, $curAction) {
    $cache = NgnCache::c();
    if (1 or ($items = $cache->load('userMenu'.$user['id'])) === false) {
    $page = DbModelCore::get('pages', 'userData', 'controller');
    $items = array(
      array(
        'title' => $page['title'],
        'link' => Tt::getUserPath($user['id']),
        'action' => 'default',
        'pageIds' => $page['id']
      )
    );
    if (!empty($page['settings']['commentsEnable'])) {
      $cnt = db()->selectCell('SELECT COUNT(*) FROM comments WHERE userId=?d', $user['id']);
      if ($cnt) {
        $items[] = array(
          'title' => 'Комментарии <b>('.$cnt.')</b>',
          'link' => Tt::getUserPath($user['id']).'/comments',
          'action' => 'comments'
        );
      }
    }
    if (!empty($page['settings']['answersEnable'])) {
      $cnt = db()->selectCell('SELECT COUNT(*) FROM comments WHERE ansUserId=?d', $user['id']);
      if ($cnt) {
        $items[] = array(
          'title' => 'Ответы <b>('.$cnt.')</b>',
          'link' => Tt::getUserPath($user['id']).'/answers',
          'action' => 'answers',
          'pageIds' => $page['id']
        );
      }
    }
    if (!empty($page['settings']['ddItemsPageIds'])) {
      $pages = DbModelCore::collection('pages',
        DbCond::get()->addF('id', $page['settings']['ddItemsPageIds']));
      foreach ($pages as $page) {
        $cnt = db()->selectCell('SELECT COUNT(*) FROM '.DdCore::table($page['strName']).'
          WHERE active=1 AND pageId=?d AND userId=?d', $page['id'], $user['id']);
        if (!$cnt) {
          if ($page['mysite']) {
            // Не показываем "mysite" раздел, если он не наш
            if (Auth::get('id') != $user['id']) {
              continue;
            }
          } else {
            continue;
          }
        }
        $title = (!empty($page['settings']['userDataBookmarkTitle']) ?
          $page['settings']['userDataBookmarkTitle'] : $page['title']);
        $linkBase = $page['mysite'] ?
          'http://'.$user['name'].'.'.SITE_DOMAIN.'/'.$page['path'] :
          'http://'.SITE_DOMAIN.'/'.$page['path'];
        if (!empty($page['settings']['oneItemFromUser'])) {
          $itemId = db()->selectCell(
            'SELECT id FROM '.DdCore::table($page['strName']).' WHERE userId=?d', $user['id']);
          $items[] = array(
            'title' => $title,
            'link' => $linkBase.'/'.$itemId,
            'pageIds' => array($page['id'])
          );
        } else {
          $items[] = array(
            'title' => $title.($cnt ? ' <b>('.$cnt.')</b>' : ''),
            'link' => $linkBase.'/u.'.$user['id'],
            'pageIds' => array($page['id'])
          );
        }
        if (!empty($page['settings']['slavePageId']))
          $items[count($items)-1]['pageIds'][] = $page['settings']['slavePageId'];
      }
    }
      $cache->save($items, 'userMenu'.$user['id']);
    }
    return array_map(function($v) use ($curPageId, $curAction) {
      if (in_array($curPageId, (array)$v['pageIds'])/* and $v['action'] == $curAction*/) $v['selected'] = true;
      return $v;
    }, $items);
  }
  
}
