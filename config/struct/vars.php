<?php

/**
 * Формат "visibilityConditions":
 * array(
     'headerName' => 'имя поля хедера',
     'condFieldName' => 'имя элемента условие для которго проверяется',
     'cond' => 'условие (javascript-код)',
   )
 * 
 */

return array(
  // Тестовые
  'showItemsOnMap' => array(
    'type' => 'array', 
    'title' => 'Страницы', 
    'fields' => array(
      'page' => array(
        'title' => 'Раздел', 
        'type' => 'page'
      ), 
      'dummy' => array(
        'title' => 'dummy'
      )
    )
  ), 
  'lang-admin-en' => array(
    'type' => 'hash'
  ), 
  // Стандартные 
  'developer-ips' => array(
    'title' => 'IP адреса разработчиков'
  ), 
  'lang' => array(
    'title' => 'Языки', 
    'static' => true, 
    'fields' => array(
      'admin' => array(
        'title' => 'Язык панели управления', 
        'type' => 'select', 
        'options' => array(
          'ru' => 'Русский', 
          'en' => 'Английский'
        )
      )
    )
  ), 
  'hideOnlineStatusUsers' => array(
    'type' => 'array', 
    'title' => 'Не показывать в списке онлайн-пользователей', 
    'fields' => array(
      array(
        'type' => 'user'
      )
    )
  ), 
  'adminExtras' => array(
    'title' => 'Админ: дополнения', 
    'static' => true, 
    'fields' => array(
      'homeHtml' => array(
        'title' => 'Дополнительный код для главной страницы админки', 
        'type' => 'textarea',
        'maxlength' => 10000
      )
    )
  ), 
  'admins' => array(
    'type' => 'array', 
    'title' => 'Админы', 
    'fields' => array(
      array(
        'type' => 'user'
      )
    )
  ), 
  'gods' => array(
    'type' => 'array', 
    'title' => 'Боги', 
    'fields' => array(
      array(
        'type' => 'user'
      )
    )
  ), 
  'layout' => array(
    'type' => 'hash', 
    'static' => true, 
    'title' => 'Оформление', 
    'fields' => array(
      'pageTitleFormat' => array(
        'title' => 'Вид отображения заголовка в теге TITLE', 
        'type' => 'select',
        'default' => 1,
        'options' => array(
          1 => 'Название сайта — Имя страницы',
          2 => 'Имя страницы — Название сайта',
        )
      ),
      'isDevPanel' => array(
        'title' => 'Наличие панели разработчика', 
        'type' => 'bool'
      ),
      'enableShareButton' => array(
        'title' => 'Включить кнопку "Поделиться"', 
        'type' => 'bool'
      )
    )
  ),
  /*
   * @depricated
  'layoutColsBlocks' => array(
    'type' => 'array', 
    'title' => 'Колонки', 
    'fields' => array(
      array(
        'type' => 'num',
        'title' => 'Ширина колонки (от 1 до 24)'
      )
    )
  ), 
   */
  'rating' => array(
    'title' => 'Рейтинг', 
    'static' => true, 
    'fields' => array(
      'ratingVoterType' => array(
        'title' => 'Тип голосования', 
        'type' => 'select', 
        'options' => array(
          'simple' => 'Голосовать может любой посетитель', 
          'auth' => 'Голосовать может любой авторизованый пользователь', 
          'level' => 'Голосовать может любой пользователь с уровнем выше нуля'
        )
      ), 
      'maxStarsN' => array(
        'title' => 'Максимальное количество звёзд для голосования. Используется в том случае, если используется тип голосования без ограничений по уровню', 
        'type' => 'num' 
      ),
      'isMinus' => array(
        'title' => 'Минусовое голосование',
        'type' => 'bool'
      ),
      'allowVotingLogForAll' => array(
        'title' => 'Разрешить просмотр лога голосований для всех',
        'type' => 'bool'
      ),
      'grade' => array(
        'title' => 'Настройки оценки (только для типа с авторизованными пользователями)',
        'type' => 'header'
      ),
      'gradeEnabled' => array(
        'title' => 'Оценка включена',
        'type' => 'bool'
      ),
      'gradeBegin' => array(
        'type' => 'header'
      ),
      'gradeSetPeriod' => array(
        'title' => 'Период по истечении которого выставляется оценка',
        'type' => 'select',
        'options' => array(
          86400 => 'сутки',
          259200 => '3 дня',
          604800 => 'неделя',
          1209600=> '2 недели',
          2592000 => 'месяц',
          5184000 => '2 месяца',
          7776000 => '3 месяца',
          15552000 => '6 месяцев',
          31104000 => 'год',
        )
      ),
      'gradeSetDay' => array(
        'title' => 'День недели для назначения оценки (время: 4 утра)',
        'type' => 'select',
        'options' => Arr::filter_key_in_array(
          Misc::weekdays(), array(1, 6, 7)
        ),
      ),
      'grade5percent' => array(
        'title' => '% от всех записей за указанный период, набирающих 5 баллов',
        'type' => 'num'
      ),
      'grade4percent' => array(
        'title' => '% от всех записей за указанный период, набирающих 4 балла',
        'type' => 'num'
      ),
      'grade3percent' => array(
        'title' => '% от всех записей за указанный период, набирающих 3 балла',
        'type' => 'num'
      ),
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'gradeBegin',
        'condFieldName' => 'gradeEnabled',
        'cond' => 'v == true',
      )
    )    
  ), 
  'level' => array(
    'title' => 'Уровни', 
    'static' => true, 
    'fields' => array(
      'on' => array(
        'title' => 'Включены', 
        'type' => 'bool',
        'default' => false,
      ),
      'interval' => array(
        'title' => 'Интервал для сбора данных для назначения уровня', 
        'type' => 'select',
        'options' => array(
          43200 => '12 часов',
          86400 => 'сутки',
          172800 => '2 суток',
          604800 => 'неделя',
          1209600 => '2 недели',
          2592000 => 'месяц',
          7776000 => '3 месяца',
          15552000 => '6 месяцев',
          31104000 => 'год',
          62208000 => '2 года',
          93312000 => '3 года'
        ),
        'default' => 43200
      ),
      'avatars' => array(
        'title' => 'Добавлять иконку уровня на аватар',
        'type' => 'bool'
      ),
      'commentsTagsLayer2Level' => array(
        'title' => 'Уровень для дополнительных тэгов <!-- 2-го ранга --> в комментариях',
        'type' => 'select',
        'options' => array(
          1 => 1,
          2 => 2,
          3 => 3,
          4 => 4,
          5 => 5,
          6 => 6,
          7 => 7,
          8 => 8,
          9 => 9,
          10 => 10,
        ),
      ),
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'begin',
        'condFieldName' => 'on',
        'cond' => 'v == true',
      )
    )    
  ), 
  'levelStars' => array(
    'title' => 'Уровни: звёзды', 
    'type' => 'array', 
    'fields' => array(
      'level' => array(
        'title' => 'Уровень', 
        'type' => 'num'
      ), 
      'maxStarsN' => array(
        'title' => 'Максимальное количество звёзд за раз', 
        'type' => 'num'
      )
    )
  ),
  'dd' => array(
    'title' => 'Динамические данные', 
    'static' => true, 
    'fields' => array(
      'forceCache' => array(
        'title' => 'Выключить кэш', 
        'type' => 'bool'
      ),
      'typo' => array(
        'title' => 'Типографирование значений форм и стриппинг допустимых тэгов', 
        'type' => 'bool'
      ),
      'allowEditSystemDates' => array(
        'title' => 'Разрешить изменение системных дат', 
        'type' => 'bool'
      ),      
      'fancyUploader' => array(
        'title' => 'Загрузка файлов со статусом загрузки', 
        'type' => 'bool'
      ),      
      'itemsN' => array(
        'title' => 'Кол-во записей на странице по умолчанию',
        'type' => 'select',
        'options' => array(
          3=>3, 5=>5, 10=>10, 15=>15, 20=>20, 30=>30, 40=>40, 50=>50, 100=>100, 200=>200, 300=>300, 1000=>1000, 9999999 => 'очень много'
        ),
        'default' => 30
      ),
      'enableSubscribe' => array(
        'title' => 'Включить подписку',
        'type' => 'bool',
        'default'
      ),
      'contentWidth' => array(
        'title' => 'Ширина контентной области', 
        'type' => 'num', 
        'default' => 600
      ),
      'smW' => array(
        'title' => 'Ширина превьюшки', 
        'type' => 'num', 
        'default' => 100
      ),
      'smH' => array(
        'title' => 'Высота превьюшки', 
        'type' => 'num', 
        'default' => 80
      ),
      'mdW' => array(
        'title' => 'Ширина уменьшенной копии', 
        'type' => 'num', 
        'default' => 600
      ), 
      'mdH' => array(
        'title' => 'Высота уменьшенной копии', 
        'type' => 'num', 
        'default' => 400
      ), 
      'resizeType' => array(
        'title' => 'Метод создания превьюшки по умолчанию', 
        'type' => 'select', 
        'default' => 'resize', 
        'options' => array(
          'resize' => 'Обрезать', 
          'resample' => 'Вписывать'
        )
      ),
      'enableNotify' => array(
        'title' => 'Включить возможность подписки на обновление разделов',
        'type' => 'bool',
        'default' => false
      ),
      'useFieldNameAsItemClass' => array(
        'title' => 'Использовать значения следующие полей в качестве классов записей',
        'type' => 'fieldSet',
        'fields' => array(
          array(
            'name' => 'field',
            'title' => 'Поле'
          )
        )
      )
    )
  ), 
  'ddLayouts' => array(
    'type' => 'hash', 
    'title' => 'DD-Layouts', 
    'fields' => array(
      array(
        'title' => 'layouts',
        'type' => 'fieldSet',
        'fields' => array(
          array(
            'title' => 'Имя',
            'name' => 'name',
            'type' => 'name',
          ),
          array(
            'title' => 'Название',
            'name' => 'title'
          ),
        )
      )
    )
  ), 
  'watermark' => array(
    'title' => 'Водяной знак', 
    'type' => 'hash', 
    'static' => true, 
    'fields' => array(
      'enable' => array(
        'title' => 'Вкоючен', 
        'type' => 'bool', 
        'default' => false
      ), 
      'begin' => array('type' => 'header'),
      'rightOffset' => array(
        'title' => 'Отступ от правой границы изображения до водяного знака', 
        'type' => 'num', 
        'default' => 10
      ), 
      'bottomOffset' => array(
        'title' => 'Отступ от нижней границы изображения до водяного знака', 
        'type' => 'num', 
        'default' => 10
      ), 
      'q' => array(
        'title' => 'Качество JPEG (от 0 до 100)', 
        'type' => 'num', 
        'default' => 100
      ), 
      'path' => array(
        'title' => 'Путь до изображения относительно www корня'
      )
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'begin',
        'condFieldName' => 'enable',
        'cond' => 'v == true',
      )
    )
  ), 
  'eventsInfo' => array(
    'type' => 'hash', 
    'title' => 'Шаблоны событий', 
    'fields' => array(
      'title' => array(
        'title' => 'Заголовок', 
        'type' => 'text'
      ), 
      'text' => array(
        'title' => 'Текст', 
        'type' => 'textarea'
      )
    )
  ), 
  'mail' => array(
    'title' => 'Почта', 
    'static' => true, 
    'fields' => array(
      'method' => array(
        'title' => 'Метод', 
        'type' => 'select', 
        'options' => array(
          'mail' => 'mail()', 
          'smtp' => 'SMTP'
        )
      ),
      'from' => array(
        'title' => 'Данные для поля "от кого" автоматически рассылаемых писем',
        'type' => 'header'
      ),
      'fromEmail' => array(
        'title' => 'E-mail',
        'type' => 'text',
        'required' => true
      ),
      'fromName' => array(
        'title' => 'Имя',
        'required' => true
      ),
    )
  ), 
  'smtp' => array(
    'title' => 'SMTP', 
    'static' => true, 
    'fields' => array(
      'server' => array(
        'title' => 'Сервер'
      ),
      'port' => array(
        'title' => 'Порт',
        'type' => 'num',
        'help' => '0 - использовать по умолчанию 25 порт'
      ),
      'auth' => array(
        'title' => 'Включить SMTP авторизацию',
        'type' => 'bool'
      ),
      'authBegin' => array(
        'type' => 'header'
      ),
      'user' => array(
        'title' => 'Пользователь'
      ), 
      'pass' => array(
        'title' => 'Пароль', 
        'type' => 'password'
      ),
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'authBegin',
        'condFieldName' => 'auth',
        'cond' => 'v == true',
      )
    )
  ), 
  'subscribe' => array(
    'title' => 'Рассылка', 
    'static' => true, 
    'fields' => array(
      'onReg' => array(
        'title' => 'Включить подписку на листы рассылок при регистрации', 
        'type' => 'bool',
        'default' => false
      ),
      'reg' => array('type' => 'header'),
      'regHeaderTitle' => array(
        'title' => 'Заголовок для секции регистрации',
      ),
      'other' => array('type' => 'header'),
      'jobsInStep' => array(
        'title' => "По сколько писем отправлять за один запрос", 
        'type' => 'num',
        'default' => 5
      )
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'reg',
        'condFieldName' => 'onReg',
        'cond' => 'v == true',
      )
    )
  ),
  'userReg' => array(
    'title' => 'Регистрация/профиль', 
    'static' => true, 
    'fields' => array(
      'activation' => array(
        'title' => 'Активация после регистрации', 
        'type' => 'select',
        'options' => array(
          '' => 'отключена',
          'email' => "по email'у",
          'admin' => 'только администратором' 
        )
      ), 
      'emailEnable' => array(
        'title' => "Включить заполнение e-mail'a при регистрации",
        'type' => 'bool'
      ),
      'phoneEnable' => array(
        'title' => "Включить заполнение телефона при регистрации",
        'type' => 'bool'
      ),
      'loginAsFullName' => array(
        'title' => "Использовать логин, как полное имя",
        'type' => 'bool'
      ),
      'authorizeAfterReg' => array(
        'title' => 'Авторизовывать после регистрации',
        'type' => 'bool'
      ),
      'vkAuthEnable' => array(
        'title' => 'Включить авторизацию Вконтакте',
        'type' => 'bool'
      ),
      'allowLoginEdit' => array(
        'title' => 'Разрешить изменение логина', 
        'type' => 'bool'
      ), 
      'allowPassEdit' => array(
        'title' => 'Разрешить изменение пароля', 
        'type' => 'bool'
      ), 
      'allowEmailEdit' => array(
        'title' => 'Разрешить изменение e-mail', 
        'type' => 'bool'
      ),
      'allowPhoneEdit' => array(
        'title' => 'Разрешить изменение телефона', 
        'type' => 'bool'
      ),
      'allowNameEdit' => array(
        'title' => 'Разрешить изменение домена', 
        'type' => 'bool'
      ),
      'allowMysiteThemeEdit' => array(
        'title' => 'Разрешить изменение оформления Моего сайта', 
        'type' => 'bool'
      ),
      'pageIds' => array(
        'title' => 'Дополнительные разделы в блоке авторизованого пользователя',
        'type' => 'fieldList',
        'fieldsType' => 'pageId',
      ),
      'redirectToFirstPage' => array(
        'title' => 'Перенаправлять после авторизации с фронтенда на первый раздел из указанных выше',
        'type' => 'bool'
      )
    )
  ),
  'role' => array(
    'title' => 'Роли', 
    'static' => true, 
    'fields' => array(
      'enable' => array(
        'title' => 'Включить роль', 
        'type' => 'bool'
      ),
      'role' => array('type' => 'header'),
      'roles' => array(
        'title' => 'Роли',
        'type' => 'fieldSet',
        'fields' => array(
          array(
            'title' => 'Имя роли',
            'name' => 'name',
            'type' => 'name'
          ),
          array(
            'title' => 'Название роли',
            'name' => 'title'
          ),
          array(
            'title' => 'Описание роли',
            'name' => 'text',
            'type' => 'textarea'
          ),
        )
      ),
      'priv' => array(
        'title' => 'Привелегии',
        'type' => 'fieldSet',
        'fields' => array(
          array(
            'title' => 'Имя роли',
            'name' => 'role',
            
            //'type' => 'select',
            //'options' => Config::getVarVar('role', 'roles', true)
          ),
          array(
            'title' => 'Раздел',
            'name' => 'pageId',
            'type' => 'pageId'
            
            //'type' => 'select',
            //'options' => Config::getVarVar('role', 'roles', true)
          ),
          array(
            'title' => 'Разрешенная привелегия',
            'name' => 'priv'
          )
        )
      )
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'role',
        'condFieldName' => 'enable',
        'cond' => 'v == true',
      )
    )
  ),
  'adminPriv' => array(
    'title' => 'Админ: привелегии',
    'static' => true, 
    'fields' => array(
      array('type' => 'col'),
      array(
        'name' => 'allowedAdminModules',
        'title' => 'Доступные модули панели управления',
        'type' => 'fieldList',
        'fieldsType' => 'adminModules'
      ),
      array('type' => 'col'),
      array(
        'name' => 'allowedPageModules',
        'title' => 'Доступные модули разделов сайта',
        'type' => 'fieldSet',
        'fields' => array(array(
          'title' => 'Модуль',
          'name' => 'module',
          'type' => 'pageModules'
        ))
      ),
      /*
      array(
        'name' => 'allowedPageConstructors',
        'title' => 'Доступные контроллеры разделов',
        'type' => 'fieldSet',
        'fields' => array(array(
          'title' => 'Контроллер',
          'name' => 'module',
          'type' => 'pageControllers'
        ))
      ),
      array(
        'name' => 'allowedConfigVars',
        'title' => 'Доступные секции конфигурации',
        'type' => 'fieldSet',
        'fields' => array(array(
          'title' => 'Имя секции',
          'name' => 'module',
          'type' => 'configVarNames'
        ))
      )
      */
    )
  ),
  'tiny' => array(
    'title' => 'Визуальный редактор', 
    'fields' => array(
      'typo' => array(
        'title' => 'Типографировать текст',
        'type' => 'bool'
      )
    )
  ),
  'tiny.admin.allowedTags' => array(
    'title' => 'Админ: доступные HTML-тэги'
  ), 
  'tiny.admin.classes' => array(
    'title' => 'Админ: доступные CSS-классы', 
    'fields' => array(
      'title' => array(
        'title' => 'Название'
      ), 
      'class' => array(
        'title' => 'Класс'
      )
    )
  ), 
  'tiny.admin.disableBtns' => array(
    'title' => 'Админ: выключеные кнопки', 
    'fields' => array(
      array(
        'type' => 'select', 
        'options' => array(
          '' => '—', 
          'anchor' => 'anchor', 
          'outdent' => 'outdent', 
          'indent' => 'indent', 
          'strikethrough' => 'strikethrough', 
          'justifycenter' => 'justifycenter', 
          'justifyfull' => 'justifyfull', 
          'help' => 'help'
        )
      )
    )
  ), 
  // Portal-версия
  /*
   * 
   * @depricated
   
  'colors' => array(
    'title' => 'Цвета темы', 
    'static' => true, 
    'fields' => array(
      'color1' => array(
        'title' => 'Фон', 
        'type' => 'color'
      ), 
      'color2' => array(
        'title' => 'Серый, фон пути, горизонтальная линия, фон бокса', 
        'type' => 'color'
      ), 
      'color3' => array(
        'title' => 'Ссылка, серый hover, тёмно-серый, фон сабменю', 
        'type' => 'color'
      ), 
      'color4' => array(
        'title' => 'Текст, тёмно-серый hover, фон кнопки 1, ссылка сабменю, текст пути, фон ссылок на страницы, текст бокса, заголовок бокса', 
        'type' => 'color'
      ), 
      'color5' => array(
        'title' => 'Фон страницы, текст кнопки 1, текст кнопки 2, фон меню, текст заголовка, текст ссылок на страницы', 
        'type' => 'color'
      ), 
      'color6' => array(
        'title' => '...', 
        'type' => 'color'
      ), 
      'color7' => array(
        'title' => 'Фон кнопки 2, фон заголовка, фон h2', 
        'type' => 'color'
      ), 
      'color8' => array(
        'title' => '...', 
        'type' => 'color'
      )
    )
  ), 
  */
  'plusItemsDefault' => array(
    'type' => 'hash', 
    'static' => true, 
    'title' => 'Присвоение плюсов за записи', 
    'static' => true, 
    'fields' => array(
      'n' => array(
        'title' => 'Кол-во записей за единицу времени, которое будет давать плюсы', 
        'type' => 'num'
      ), 
      't' => array(
        'title' => 'Единица времени (секунд)', 
        'type' => 'num'
      ), 
      'e' => array(
        'title' => 'Кол-во плюсов, получаемое в результате добавления n работ за t время', 
        'type' => 'num'
      )
    )
  ), 
  'commentsPages' => array(
    'type' => 'array', 
    'title' => 'Разделы для последних комментариев', 
    'fields' => array(
      'title' => array(
        'title' => 'Раздел', 
        'type' => 'pageId'
      )
    )
  ),
  'pageBlocksSettings' => array(
    'title' => 'Размеры блоков по умолчанию', 
    'static' => true, 
    'fields' => array(
      'colsN' => array(
        'title' => 'Количество колонок', 
        'type' => 'select',
        'options' => array(
          1 => 1,
          2 => 2,
          3 => 3,
          4 => 4,
          5 => 5,
        )
      ), 
      'w' => array(
        'title' => 'Ширина всех блоков вместе (пикселей)', 
        'type' => 'num'
      ), 
      'rh' => array(
        'title' => 'Высота единичного блока (пикселей)', 
        'type' => 'num'
      ), 
      'mr' => array(
        'title' => 'Отступ справа от блока', 
        'type' => 'num'
      ),
      'mb' => array(
        'title' => 'Отступ снизу от блока', 
        'type' => 'num'
      )
    )
  ),
  'sape' => array(
    'type' => 'hash', 
    'static' => true, 
    'title' => 'SAPE', 
    'static' => true, 
    'fields' => array(
      'enable' => array(
        'title' => 'Включено', 
        'type' => 'bool', 
      ),
      'begin' => array('type' => 'header'),      
      'code' => array(
        'title' => 'Код', 
      ),
      'multiSite' => array(
        'title' => 'Мультисайт',
        'type' => 'bool'
      ),
      'linksN' => array(
        'title' => 'Количество ссылок',
        'type' => 'num'
      ) 
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'begin',
        'condFieldName' => 'enable',
        'cond' => 'v == true',
      )
    )
  ),
  'grabber' => array(
    'type' => 'hash', 
    'static' => true, 
    'title' => 'Граббер', 
    'fields' => array(
      'enable' => array(
        'title' => 'Включено', 
        'type' => 'bool', 
      ),
      'begin' => array('type' => 'header'),      
      'period' => array(
        'title' => 'Частота сбора данных',
        'type' => 'select',
        'options' => Arr::to_options(CronPeriod::getPeriods(), 'title')
      ),
      'attemptsBeforeDisactivate' => array(
        'title' => 'Количество неудачных попыток до отключения канала',
        'type' => 'select',
        'options' => array(
          1 => 1,
          2 => 2,
          5 => 5,
          10 => 10,
          30 => 30,
          100 => 100
        )
      ),
      'admin' => array(
        'title' => 'Пользователь, получающий уведомления об ошибках',
        'type' => 'user',
      ),
      'itemsLimit' => array(
        'title' => 'Лимит записей получаемых при каждой проверке обновлений на канале (значение по умолчанию)',
        'type' => 'num'
      )
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'begin',
        'condFieldName' => 'enable',
        'cond' => 'v == true',
      )
    ),
  ),
  'mysite' => array(
    'type' => 'hash', 
    'static' => true, 
    'title' => 'Мой сайт', 
    'fields' => array(
      'enable' => array(
        'title' => 'Включен', 
        'type' => 'bool', 
      ),
      'allowHomeRedefineByOwner' => array(
        'title' => 'Разрешить переопределение домашней странички владельцем Моего сайта',
        'type' => 'bool'
      ),
      'homeType' => array(
        'title' => 'Тип домашней странички',
        'type' => 'select',
        'options' => array(
          'userData' => 'Профиль',
          'items' => 'Записи',
          'blocks' => 'Блоки',
        )
      ),
      'pageBegin' => array('type' => 'header'),
      'homePageId' => array(
        'title' => 'Раздел для домашней странички', 
        'type' => 'page'
      ),
      //'pageEnd' => array('type' => 'header'), 
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'pageBegin',
        'condFieldName' => 'homeType',
        'cond' => 'v == "items"',
      )
    )
  ),
  'mysiteReservedSubdomains' => array(
    'type' => 'array', 
    'title' => 'Мой сайт: зарезервированые сабдомены', 
    array(
      array(
        'title' => 'Сабдомен', 
      ),
    ) 
  ),
  'notify' => array(
    'type' => 'hash', 
    'static' => true, 
    'title' => 'Уведомления', 
    'fields' => array(
      'enable' => array(
        'title' => 'Включены', 
        'type' => 'bool', 
      ),
    )
  ),
  'event' => array(
    'type' => 'hash', 
    'static' => true, 
    'title' => 'События', 
    'fields' => array(
      'forceModerSubscribe' => array(
        'title' => 'Принудительная отправка уведомления не обращая внимания на подписку', 
        'type' => 'bool', 
      ),
    )
  ),
  'theme' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Тема',
    'fields' => array(
      'enabled' => array(
        'title' => 'Включена', 
        'type' => 'bool',
        'default' => false,
      ),
      'begin' => array('type' => 'header'), 
      'theme' => array(
        'title' => 'Тема',
        'type' => 'stmThemeSelect'
      )
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'begin',
        'condFieldName' => 'enabled',
        'cond' => 'v == true',
      )
    )
  ),
  'url' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'URL',
    'fields' => array(
      'cache' => array(
        'title' => 'Кэшировать',
        'type' => 'bool',
      ),
      'sitePreviewUrl' => array(
        'title' => 'Ссылка на генератор превьюшек веб-страниц',
      ),
    )
  ),
  'google' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Google',
    'fields' => array(
      'mapKey' => array(
        'title' => 'Google Map Key',
      )
    )
  ),
  'yandex' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Яндекс',
    'fields' => array(
      'verification' => array(
        'title' => 'Код подтверждения подлинности сайта',
      )
    )
  ),
  'menu' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Меню',
    'fields' => array(
      'useTagsAsSubmenu' => array(
        'title' => 'Использовать тэги раздела в качестве подразделов меню',
        'type' => 'bool'
      )
    )
  ),
  'piwik' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Сервер статистики',
    'fields' => array(
      'url' => array(
        'title' => 'Ссылка',
        'type' => 'url'
      ),
      'authToken' => array(
        'title' => 'token авторизации',
      )
    )
  ),
  'stat' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Статистика',
    'fields' => array(
      'enable' => array(
        'title' => 'Включена',
        'type' => 'bool'
      ),
      'siteId' => array(
        'title' => 'ID сайта в системе статистики',
        'help' => 'определяется автоматически, при включении статистики',
        'type' => 'hidden'
      )
    )
  ),
  'vk' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Вконтакте',
    'fields' => array(
      'appId' => array(
        'title' => 'ID приложения',
      ),
      'secKey' => array(
        'title' => 'Защищенный ключ',
      )
    )
  ),
  'vkAuth' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Вконтакте: авторизация',
    'fields' => array(
      'login' => array(
        'title' => 'Логин / email',
      ),
      'pass' => array(
        'title' => 'Пароль',
        'type' => 'password'
      )
    )
  ),
  'store' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Магазин',
    'fields' => array(
      'orderControllerSuffix' => array(
        'title' => 'Контроллер заказа',
        'type' => 'storeOrderControllerSuffix'
      ),
      'ordersPageId' => array(
        'title' => 'Раздел с базой заказов',
        'type' => 'pageId'
      )
    )
  ),
  'userStore' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Пользовательский магазин',
    'fields' => array(
      'enable' => array(
        'title' => 'Включен',
        'type' => 'bool'
      ),
      'begin' => array('type' => 'header'), 
      'roles' => array(
        'title' => 'Роли пользователей, имеющих доступ к магазину',
        'type' => 'roleMultiselect'
      ),
    ),
    'visibilityConditions' => array(
      array(
        'headerName' => 'begin',
        'condFieldName' => 'enable',
        'cond' => 'v == true',
      )
    )
  ),
  'userGroup' => array(
    'type' => 'hash',
    'static' => true,
    'title' => 'Сообщества',
    'fields' => array(
      'enable' => array(
        'title' => 'Включены',
        'type' => 'bool'
      )
    ),
  )
);
