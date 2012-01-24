<?php

class DispatcherManager {
  
  /**
   * @var   string  site/admin
   */
  static public $defaultDispatcher = 'site';
  
  /**
   * Создаёт и возвращает объекта Диспетчера в зависимости 
   * от параметров пути
   *
   */
  static public function get() {
    $request = O::get('Req');
    // Общедоступные по HTTP скрипты
    if (isset($request->params[0]) and in_array($request->params[0],
                 array('s', 's2', 'c', 'c2'))) {
      $dispatcher = new DispatcherScripts();
      
    // Страницы админки
    } elseif (isset($request->params[0]) and ($request->params[0] == 'admin' or 
              $request->params[0] == 'god')) {
      $dispatcher = new DispatcherAdmin();

    // Страницы сайта
    } else {
      if (self::$defaultDispatcher == 'site') {
        $dispatcher = new DispatcherSite();
      } else {
        $dispatcher = new DispatcherAdmin();
      }
    }
    return $dispatcher;
  }

}