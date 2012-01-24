<?php

class PrivilegesManager extends PrivilegesManager_Common {
  
  function create($userId, $pageId, $type) {
    parent::create($userId, $pageId, $type);
    // Добавляем привилегии для привилегированых полей
    // только если это привилегия редактирования
    if ($type != 'edit') return;
    $page = DbModelCore::get('pages', $pageId);
    if (empty($page['strName'])) return;
    DdPrivilegesManager::addPrivs_strName($userId, $page['strName']);
  }
  
}