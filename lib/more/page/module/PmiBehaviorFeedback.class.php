<?php

class PmiBehaviorFeedback extends PmiBehaviorAbstract {
  
  public function action($pageId, $node) {
    $oP = new PrivilegesManager();
    $oP->create(ALL_USERS_ID, $pageId, 'create');
    
    if (!isset($node['params'][2])) {
      // Если пользователь не определен, определяем получателем первого админа
      $moderId = Arr::first(Config::getVar('admins'));
    } else {
      // Создание пользователя
      $moderId = DbModelCore::create('users', array(
        'login' => $node['params'][2],
        'pass' => '123',
        'email' => $node['params'][2],
        'active' => true
      ));
    }
    // Добавление для него прав модерирования
    $oP->create($moderId, $pageId, 'moder');
  }
  
}

