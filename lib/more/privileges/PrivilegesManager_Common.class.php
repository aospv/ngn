<?php

class PrivilegesManager_Common extends Privileges {
  
  /**
   * Получает массив с привилегии по заданному пользователю
   *
   * @param   integer   ID пользователя
   * @return  array     Массив с привилегиями
   */
  function &getByUser($userId) {
    $privs = array();
    foreach (db()->select("
    SELECT pageId, type FROM privs WHERE userId=?d", $userId) as $k => $v) {
      $privs[$v['pageId']]['types'][] = $v['type'];
    }
    $this->decribePages($privs);
    return $privs;
  }
  
  function getByPage($pageId) {
    $privs = array();
    foreach (db()->select("
    SELECT userId, type FROM privs WHERE pageId=?d", $pageId) as $k => $v) {
      $privs[$v['userId']]['types'][] = $v['type'];
    }
    $this->decribeUsers($privs);
    return $privs;
  }
  
  function getAll() {
    $privPages = array();
    foreach (db()->query("
      SELECT
        privs.*,
        pages.title,
        pages2.title AS title2,
        pages.id AS pageExists,
        users.login
      FROM privs
      LEFT JOIN pages ON privs.pageId=pages.id
      LEFT JOIN pages AS pages2 ON pages.parentId=pages2.id
      LEFT JOIN users ON privs.userId=users.id
      ") as $k => $v) {
      $privPages[$v['pageId']]['pageTitle'] = $v['title'].($v['title2'] ? ' ← '.$v['title2'] : '');
      $privPages[$v['pageId']]['pageExists'] = $v['pageExists'] ? true : false;
      $privPages[$v['pageId']]['users'][$v['userId']]['login'] = $v['login'];
      $privPages[$v['pageId']]['users'][$v['userId']]['types'][] = $v['type'];
    }
    return $privPages;
  }
  
  /**
   * Добавляет в массив с привилегиями заголовки разделов, для которых они назначены
   *
   * @param   array   Массив с привилегиями:
   *                  array(
   *                    pageId => array(
   *                      title => 'Заголовок'
   *                      types => array(
   *                        'edit', 'create', ...
   *                      )
   *                    )
   *                  )
   */
  function decribePages(&$privs) {
    if (!$privs) return;
    $ids = array_keys($privs);
    $pages = db()->selectCol("SELECT id AS ARRAY_KEY, title FROM pages
    WHERE id IN (".implode(',', $ids).")");
    if (!$pages) return;
    foreach ($pages as $id => $title) {
      $privs[$id]['title'] = $title;
    }
  }
  
  function decribeUsers(&$privs) {
    if (!$privs) return;
    $ids = array_keys($privs);
    $users = db()->selectCol("
      SELECT id AS ARRAY_KEY, login FROM users
      WHERE id IN (".implode(',', $ids).")");
    if (!$users) return;
    foreach ($users as $id => $login) {
      $privs[$id]['login'] = $login;
    }
  }
  
  /**
   * Добавляет привилегию "раздел - пользователь"
   *
   * @param   integer   ID пользователя
   * @param   integer   ID страницы
   * @param   string    Привилегия (Например: 'edit')
   */
  function create($userId, $pageId, $type) {
    db()->query(
      "REPLACE INTO privs SET userId=?, pageId=?, type=?",
      $userId, $pageId, $type);
  }
  
  /**
   * Добавляет привилегии "раздел - пользователь", удаляя перед этим 
   * все привилегии для этого пользователя на этом разделе
   *
   * @param   integer   ID пользователя
   * @param   integer   ID страницы
   * @param   array     Привилегии
   *                    Например:
   *                    array(
   *                      pageId => array(
   *                        'edit' => 1,
   *                        'create' => 1
   *                      )
   *                    )
   */
  function addPrivsByUser($userId, $privs) {    
    db()->query("DELETE FROM privs WHERE userId=?d", $userId);
    if (empty($privs) or !is_array($privs)) return;
    foreach ($privs as $pageId => $types) {
      foreach ($types as $type => $v) {
        $this->create($userId, $pageId, $type);
      }
    }
  }
  
  /**
   * Добавляет привилегии "раздел - пользователь", удаляя перед этим 
   * все привилегии для этого пользователя на этом разделе
   *
   * @param   integer   ID пользователя
   * @param   integer   ID страницы
   * @param   array     Привилегии
   *                    Например:
   *                    array(
   *                      userId => array(
   *                        'edit' => 1,
   *                        'create' => 1
   *                      )
   *                    )
   */
  function addPrivsByPage($pageId, $privs) {    
    db()->query("DELETE FROM privs WHERE pageId=?", $pageId);
    if (!$privs or !is_array($privs)) return;
    foreach ($privs as $userId => $types) {
      foreach ($types as $type => $v) {
        $this->create($userId, $pageId, $type);
      }
    }
  }
  
  function addPrivs($userId, $pageId, $types) {    
    if (!$types or !is_array($types)) throw new NgnException('$types not defined');
    db()->query("DELETE FROM privs WHERE userId=?d AND pageId=?d",
      $userId, $pageId);
    foreach ($types as $type => $v) {      
      $this->create($userId, $pageId, $type);
    }
  }
  
  function delete($userId, $pageId) {
    db()->query(
      "DELETE FROM privs WHERE userId=?d AND pageId=?d",
      $userId, $pageId);
  }
  
  function deleteByPage($pageId) {
    db()->query(
      "DELETE FROM privs WHERE pageId=?d",
      $pageId);
  }
  
  function cleanup() {
  	foreach ($this->getAll() as $pageId => $v) {
  		if (!$v['pageExists']) {
  			foreach ($v['users'] as $userId => $vv) {
  				$this->delete($userId, $pageId);
  			}
  		} else {
  			foreach ($v['users'] as $userId => $vv) {
  				if (!$vv['login']) $this->delete($userId, $pageId);
  			}
  		}
  	}
  }
  
}