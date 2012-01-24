<?php

class DdoMethods extends Singletone {
  
  public $field;
  
  public function __construct() {
    $this->field = array(
      'author' => array(
        'avatar' => array(
          'title' => 'Аватар',
          'dddd' => 'UsersCore::avatar($authorId, $authorLogin)'
        ),
        'avatar2' => array(
          'title' => 'Аватар 50%',
          'dddd' => 'UsersCore::avatar($authorId, $authorLogin, `halfSize`)'
        ),
        'avatarAndLogin' => array(
          'title' => 'Аватар с логином',
          'dddd' => 'UsersCore::avatarAndLogin($authorId, $authorLogin)'
        ),
        /*
        'mysite' => array(
          'title' => 'Ссылка на Мой сайт',
          'dddd' => '`<a href="http://`.$authorName.`.`.SITE_DOMAIN.`">`.$authorLogin.`</a>`'
        )
        */
      ),
      'user' => array(
        'avatar' => array(
          'title' => 'Аватар',
          'dddd' => 'UsersCore::avatar($v[`id`], $v[`login`])'
        ),
        'avatar2' => array(
          'title' => 'Аватар 50%',
          'dddd' => 'UsersCore::avatar($v[`id`], $v[`login`], `halfSize`)'
        ),
        'avatarAndLogin' => array(
          'title' => 'Аватар с логином',
          'dddd' => 'UsersCore::avatarAndLogin($v[`id`], $v[`login`])'
        ),
      ),
      'static' => array(
        'clear' => array(
          'title' => 'clear',
          'dddd' => '`<div class="clear"><!-- --></div>`'
        ),
        'h2' => array(
          'title' => 'Заголовок 2',
          'dddd' => '`<h2>`.$title.`</h2>`'
        ),
        'userDataLink' => array(
          'title' => 'Ссылка на информацию о пользователе',
          'dddd' => '`<b class="title">Автор:</b> <a href="`.Tt::getUserPath($authorId).`">`.$authorLogin.`</a>`'
        ),
        'btnOrder' => array(
          'title' => 'Кнопка "заказать"',
          'dddd' => '`<div class="iconsSet"><a href="./order/ordered/`.$id.`" class="order btn btn1"><i></i>`.$title.`</a></div>`'
        )
      ),
      'commentsCount' => array(
        'full' => array(
          'title' => 'Со словом "комментарии"',
          'dddd' => '    
$v ? (`<div class="smIcons">
<a class="gray sm-comments`.($v > 2 ? `2` : ``).`"
href="`.$pagePath.`/`.$id.`#msgs"><i></i> комментарии (`.$v.`)
</a><div class="clear"><!-- --></div>
</div>`) : ``'
        )
      ),
      'wisiwig' => array(
        'cut100' => array(
          'title' => 'Ограничение по длине 100 символов',
          'dddd' => '!empty($o->items[$id][`text`]) ? Misc::cut($v, 100, ` <a href="`.$pagePath.`/`.$id.`" class="gray more">ещё...</a>`) : ``'
        ),
        'cut200' => array(
          'title' => 'Ограничение по длине 200 символов',
          'dddd' => '!empty($o->items[$id][`text`]) ? Misc::cut($v, 200, ` <a href="`.$pagePath.`/`.$id.`" class="gray more">ещё...</a>`) : ``'
        ),
        'cut300' => array(
          'title' => 'Ограничение по длине 300 символов',
          'dddd' => '!empty($o->items[$id][`text`]) ? Misc::cut($v, 300, ` <a href="`.$pagePath.`/`.$id.`" class="gray more">ещё...</a>`) : ``'
        ),
        'cut500' => array(
          'title' => 'Ограничение по длине 500 символов',
          'dddd' => '!empty($o->items[$id][`text`]) ? Misc::cut($v, 500, ` <a href="`.$pagePath.`/`.$id.`" class="gray more">ещё...</a>`) : ``'
        ),
        'cut1000' => array(
          'title' => 'Ограничение по длине 1000 символов',
          'dddd' => '!empty($o->items[$id][`text`]) ? Misc::cut($v, 1000, ` <a href="`.$pagePath.`/`.$id.`" class="gray more">ещё...</a>`) : ``'
        ),
        'whole' => array(
          'title' => 'Весь текст',
          'dddd' => '$v'
        ),
        'wholeMore' => array(
          'title' => 'Весь текст + ссылка "читать далее" если поле "text" не пустое',
          'dddd' => '$v . (empty($o->items[$id][`text`]) ? `` : ` <a href="`.$pagePath.`/`.$id.`" class="gray more">читать далее...</a>`)'
        ),
      ),
      'typoTextarea' => array(
        'titled' => array(
          'title' => 'С заголовком',
          'dddd' => '$v ? `<b class="title">`.$title.`:</b> `.nl2br($v) : ``'
        )
      ),
      'typoText' => array(
        'text' => array(
          'title' => 'Текст',
          'dddd' => '$v'
        ),
        'h2' => array(
          'title' => 'H2',
          'dddd' => '`<h2>`.$v.`</h2>`'
        ),
        'itemLinkIfText' => array(
          'title' => 'Ссылка на запись, только если поле "text" не пустое',
          'dddd' => '(isset($o->items[$id][`text`]) and empty($o->items[$id][`text`])) ? `` : `<a href="`.$ddddItemLink.`">`.$v.`</a>`'
        ),
        'h2ItemLinkIfText' => array(
          'title' => 'Заголовок 2. Ссылка на запись, только если поле "text" не пустое',
          'dddd' => '(isset($o->items[$id][`text`]) and empty($o->items[$id][`text`])) ? `<h2>`.$v.`</h2>` : `<h2><a href="`.$ddddItemLink.`">`.$v.`</a></h2>`'
        ),
        'h3ItemLinkIfText' => array(
          'title' => 'Заголовок 3. Ссылка на запись, только если поле "text" не пустое',
          'dddd' => '(isset($o->items[$id][`text`]) and empty($o->items[$id][`text`])) ? `<h3>`.$v.`</h3>` : `<h3><a href="`.$ddddItemLink.`">`.$v.`</a></h3>`'
        ),
      ),
      'imagePreview' => array(
        'directImageLink' => array(
          'title' => 'Прямая ссылка на изображение',
          'dddd' => '$v ? `<a href="`.Misc::getFilePrefexedPath($v, `md_`, `jpg`).`" class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `sm_`, `jpg`).`" /></a>` : ``',
        ),
        'mdImageLink' => array(
          'title' => 'Изображение md',
          'dddd' => '$v ? `<a href="`.$v.`" class="thumb lightbox" target="_blank"><img src="`.Misc::getFilePrefexedPath($v, `md_`, `jpg`).`" /></a>` : ``',
        ),
        'halfSmImage' => array(
          'title' => '50% sm-изображения',
          'dddd' => '$v ? `<a href="`.$o->items[$id][`url`].`" class="thumb halfSize" target="_blank"><img src="`.Misc::getFilePrefexedPath($v, `sm_`, `jpg`).`" /></a>` : ``',
        ),
        'showDummyImage' => array(
          'title' => 'Показывать вместо отсутствующего изображения заглушку "нет фото"',
          'dddd' => '!$v ? `<a`.(empty($o->items[$id][`text`]) ? `` : ` href="`.Tt::getPath(0).`/`.$pagePath.`/`.$id.`"`).` class="thumb"><img src="/i/img/no-images.gif" /></a>` : `<a `.(empty($o->items[$id][`text`]) ? `` : ` href="`.Tt::getPath(0).`/`.$pagePath.`/`.$id.`"`).` class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `sm_`, `jpg`).`" /></a>`',
        ),
        'middleImageUrl' => array(
          'title' => 'Средняя картинка + ссылка на URL',
          'dddd' => '$v ? `<a href="`.$o->items[$id][`url`].`" class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `md_`, `jpg`).`" /></a>` : ``',
        ),
        'lightbox' => array(
          'title' => 'Lightbox',
          'dddd' => '$v ? `<a href="`.$v.`" class="thumb lightbox"><img src="`.Misc::getFilePrefexedPath($v, `sm_`, `jpg`).`" alt="`.$o->items[$id][`title`].`" title="`.$o->items[$id][`title`].`"></a>` : ``'
        )
      ),
      'url' => array(
        'previewAndLink' => array(
          'title' => 'Превью и ссылка',
          'dddd' => '$v ? `<a href="`.$v.`" class="thumb" target="_blank"><img src="`.SitePreview::url($v).`" alt="`.$o->items[$id][`title`].`" title="`.$o->items[$id][`title`].`"></a><h2><a href="`.$v.`" target="_blank">`.$o->items[$id][`title`].`</a></h2>` : ``'
        )
      ),
      /*
      'float' => array(
        'rubles' => array(
          'title' => 'Рубли',
          'dddd' => '`<b class="title">`.$title.`:</b> `.$v.` руб.`'
        ),
        'rubles' => array(
          'title' => 'Доллары',
          'dddd' => '`<b class="title">`.$title.`:</b> `.$v.` $`'
        ),
        'rubles' => array(
          'title' => 'Евро',
          'dddd' => '`<b class="title">`.$title.`:</b> `.$v.` €`'
        ),
      )
      */
      'video' => array(
        'popup' => array(
          'title' => 'Открывается в попапе',
          'tpl' => 'elements/video.popup'
        ),
        'playlist' => array(
          'title' => 'С плейлистом',
          'tpl' => 'elements/video.playlist'
        )
      ),
      'datetime' => array(
        'datetime' => array(
          'title' => 'Дата и время без заголовка',
          'dddd' => '`<span class="dgray">`.datetimeStr($o->items[$id][$name.`_tStamp`]).`</span>`'
        ),
        'date' => array(
          'title' => 'Дата без заголовка',
          'dddd' => '`<span class="dgray">`.dateStr($o->items[$id][$name.`_tStamp`]).`</span>`'
        ),
      ),
      'ddTagsSelect' => array(
        'noLink' => array(
          'title' => 'без ссылки',
          'dddd' => '$v ? `<b class="title">`.$title.`:</b> <span class="dgray">`.$v[`title`].`</span>` : ``',
        )
      ),
      'ddTags' => array(
        'iconed' => array(
          'title' => 'с иконками',
          'tpl' => 'dd/tagsList'
        )
      )
    );
    $this->field['typoTextarea'] += $this->field['wisiwig'];
    $this->field['wisiwigSimple'] = $this->field['wisiwig'];
    $this->field['ddTagsMultiselect'] = $this->field['ddTags'];
  }
  
}
