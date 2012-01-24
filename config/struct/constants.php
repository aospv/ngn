<?php

return array(
  'core' => array(
    'title' => 'Core',
    'static' => true, 
    'fields' => array(
      'DO_NOT_LOG' => array(
        'title' => 'Не вести логи', 
        'type' => 'bool'
      ),
      /*
      'LOG_OUTPUT' => array(
        'title' => 'Выводить лог', 
        'type' => 'bool',
        'default' => false
      ),
      */ 
      'IS_DEBUG' => array(
        'title' => 'Отладка', 
        'type' => 'bool',
        'default' => false
      ), 
      'DATA_CACHE' => array(
        'title' => 'Кеш данных', 
        'type' => 'bool',
        'default' => false
      ),
      'CACHE_METHOD' => array(
        'title' => 'Метод кэширования', 
        'type' => 'select',
        'default' => 'File',
        'options' => array(
          'File' => 'Файлы',
          'Memcached' => 'Memcached'
        )
      ),
      'PROJECT_KEY' => array(
        'title' => 'Ключ проекта',
        'required' => true 
      )
      /*
      'IS_MEMCACHED' => array(
        'title' => 'Мемкешед включен', 
        'type' => 'bool',
        'default' => false
      ),
      */
    )
  ),
  'more' => array(
    'title' => 'More',
    'static' => true, 
    'fields' => array(
      'DEBUG_STATIC_FILES' => array(
        'title' => 'Отладка статических файлов (кеш выключен)', 
        'type' => 'bool',
        'default' => false
      ),
      'FORCE_STATIC_FILES_CACHE' => array(
        'title' => 'Выключить кэширование статических файлов',
        'type' => 'bool',
        'default' => false
      ), 
      'TEMPLATE_DEBUG' => array(
        'title' => 'Отладка шаблонов', 
        'type' => 'bool',
        'default' => false
      ), 
      'JSON_DEBUG' => array(
        'title' => 'Отладка JSON', 
        'type' => 'bool',
        'default' => false
      ), 
      'SESSION_EXPIRES' => array(
        'title' => 'Время жизни сессии',
        'type' => 'expires',
        'default' => 60*60*24*1
      ),
      'ALLOW_GOD_MODE' => array(
        'title' => 'Позволить режим Бога', 
        'type' => 'bool',
        'default' => true
      ), 
      'ADMIN_THEME' => array(
        'title' => 'Тема для админки',
        'default' => 'admin'
      ), 
    ) 
  ),
  'site' => array(
    'title' => 'Сайт', 
    'static' => true, 
    'fields' => array(
      'SITE_TITLE' => array(
        'title' => 'Название проекта',
        'default' => 'Rename project!',
        'required' => true
      ),
      'SITE_DOMAIN' => array(
        'title' => 'Домен',
      ),
      'SITE_SET' => array(
        'title' => 'Тип сайта',
        'type' => 'select',
        'required' => true,
        'options' => array(
          'personal' => 'Персональный/корпоративный',
          'portal' => 'Портал',
        )
      ),
      'UPDATER_URL' => array( 
        'title' => 'Ссылка на NGN-апдейтер http://',
        'default' => 'masted.ru'
      ),
      /**
       * Номер первого параметра в строке запроса (начиная с нуля)
       * Т.е. если корень сайта находится в тут
       * http://site.com/folder/subfolder/, то номер первого параметра
       * должен быть равен двум. 
       */
      'FIRST_URL_PARAM_N' => array(
        'title' => 'Номер первого параметра в строке запроса', 
        'type' => 'num',
        'default' => 0
      ), 
      'EMAIL_ALLOWED' => array(
        'title' => "Разрешена отправка email'a",
        'type' => 'bool',
        'default' => true
      ),
      'ACCESS_MODE' => array(
        'title' => "Режим доступа к сайту",
        'type' => 'select',
        'options' => array(
          'all' => 'Все',
          'registered' => 'Зарегистрированые',
        ),
        'default' => 'all'
      ),      
      /* 
      'ALLCODE_ENABLE' => array(
        'title' => 'Включить сборку всего кода в один файл', 
        'type' => 'bool'
      ), 
      'DB_BLOCK_MODIF' => array(
        'title' => 'Изменение БД блокировано', 
        'type' => 'bool'
      ),
      'ADMIN_HTTP_PORT' => array(
        'title' => 'HTTP-порт админки', 
        'type' => 'num'
      ), 
      */ 
      'ROBOT_ID' => array(
        'title' => 'Обычный робот', 
        'type' => 'user',
        'default' => 1,
      ), 
      'NOTIFY_ROBOT_ID' => array(
        'title' => 'Пользователя, от имени которого рассылаются уведомления', 
        'type' => 'user',
        'default' => 1,
      ), 
      'RSS_ROBOT_ID' => array(
        'title' => 'Пользователь, от имени которого добавляются записи с RSS', 
        'type' => 'user',
        'default' => 1,
      ),
      'JS_REDIRECT' => array(
        'title' => 'Java-script редирект вместо HTTP-редиректа',
        'type' => 'bool',
        'required' => true
      ),
      'LAST_DB_PATCH' => array(
        'title' => '№ последнего примененного патча БД', 
        'type' => 'num'
      ), 
      'LAST_FILE_PATCH' => array(
        'title' => '№ последнего примененного патча файлов', 
        'type' => 'num'
      ), 
      
      
    )////////////////// ================ //////////////////
  ), 
  'database' => array(
    'title' => 'База данных', 
    'static' => true, 
    'fields' => array(
      'DB_HOST' => array(
        'title' => 'Хост'
      ), 
      'DB_NAME' => array(
        'title' => 'Имя базы'
      ), 
      'DB_USER' => array(
        'title' => 'Пользователь'
      ), 
      'DB_PASS' => array(
        'title' => 'Пароль',
        'type' => 'password'
      ),
      'DB_LOGGING' => array(
        'title' => 'Логировать SQL-запросы',
        'type' => 'bool'
      ), 
    )
  ),
  'proj' => array(
    'title' => 'Проект', 
    'static' => true, 
    'fields' => array(
      'CRON' => array(
        'title' => 'Планировщик включен',
        'type' => 'bool'
      ), 
    )
  ),
  )
;
