<?php

class Ddo {

  protected $debug = false;
  
  // -----------------------------------------------------
  
	/**
   * Массив всех полей, присутствующих в этих записях 
   *
   * @var array
   */
  public $fields;

  /**
   * Массив всех записей
   *
   * @var array
   */
  public $items;
  
  /**
   * Массив с данными раздела
   *
   * @var array
   */
  protected $page;
  
  /**
   * Массив с данными мастер-раздела
   *
   * @var array
   */
  protected $masterPage;
  
  protected $ddddItemLink;
  
  /**
   * Флаг определяет выводится ли список записей или одна запись
   *
   * @var bool
   */
  protected $list;
  
  protected $layoutName;
  
  /**
   * @var DdoSettings
   */
  protected $oSettings;
  
  public $strName;
  
  public function __construct(DbModelPages $page, $layoutName) {
    $this->oSettings = new DdoSettings($page->getModule());
    $this->layoutName = $layoutName;
    $this->page = $page;
    if (!empty($this->page['settings']['slavePageId'])) {
      $this->masterPage = DbModelCore::get('pages', $this->page['settings']['slavePageId']);
      $this->ddddItemLink = '`'.Tt::getPath(0).$this->masterPage['path'].'/v.'.
        DdCore::masterFieldName.'.`.$id';
    } else {
      $this->ddddItemLink = '$pagePath.`/`.$id';
    }
    return $this;
  }
  
  protected $pagePath;
  
  public function setPagePath($pagePath) {
    $this->pagePath = $pagePath;
    return $this;
  }

  public function setDebug($debug) {
    $this->debug = $debug;
  }
  
  protected function initFields() {
    $this->oFields = new DdoFields($this->oSettings, $this->layoutName, $this->page['strName']);
    $this->oFields->isItemsList = $this->list;
    $this->fields = $this->oFields->getFields();
    foreach ($this->fields as $v)
      if (O::exists('DdoFieldType'.ucfirst($v['type']))) {
        $class = 'DdoFieldType'.ucfirst($v['type']);
        $this->ddddByType[$v['type']] = $class::$dddd;
      }
  }
  
  public function setItem($item) {
    $this->list = false;
    $this->items = array($item['id'] => $item);
    $this->init();
    return $this;
  }
  
  public function setItems($items) {
    $this->list = true;
    $this->items = $items;
    $this->init();
    return $this;
  }
  
  protected function init() {
    $this->initFields();
    $this->initTpls();
    $this->initOutputMethodsTpls();
  }
  
  protected function initTpls() {
    $this->w = VIDEO_1_W;
    $this->h = VIDEO_1_H;
    if ($this->list) {
      if (
      !empty($this->page['settings']['smW']) and 
      !empty($this->page['settings']['smH'])
      ) {
        $this->w = $this->page['settings']['smW'];
        $this->h = $this->page['settings']['smH'];
      }
    } elseif (
      !empty($this->page['settings']['mdW']) and 
      !empty($this->page['settings']['mdH'])
    ) {
      $this->w = $this->page['settings']['mdW'];
      $this->h = $this->page['settings']['mdH'];
    }
    
    $this->w = $this->page['settings']['mdW'];
    $this->h = $this->page['settings']['mdH'];
    $this->h += 30; // отсуп панели управления скина
    
    /*
    $this->ddddByType['video'] = 
    'getTpl(`common/flashVideo`, array(
      `id` => `video`.$id,
      `path` => `./i/swf/mp/player.swf`,
      `width` => '.(int)$w.',
      `height` => '.(int)$h.',
      `bgcolor` => `#000000`,
      `flashvars` => array(
        `file` => `../../../`.str_replace(`./`, ``, $v),
        `image` => `../../../`.str_replace(`./`, ``, File::reext($v, `jpg`)),
        //`mute` => true,
        //`repeat` => `always`,
        `date` => date(`d.m.Y`, $o->items[$id][`dateCreate_tStamp`]),
        `title` => $o->items[$id][`title`],
        `author` => $authorLogin,
        `skin` => `./i/swf/mp/skin/beelden.zip`,
        `fullscreen` => true,
        `backcolor` => `#000000`
      )
    ))';
    */

    if ($this->list) {
      $this->tplPathByType['video'] = 'elements/video';
      /*
        '`<a href="`.'.$this->ddddItemLink.'.`" class="thumb">'.
        '<img src="`.str_replace(`./`, ``, File::reext($v, `jpg`)).`" '.
        ' title="`.$o->items[$id][`title`].`"><div style="display:none" class="data">`.'.
        'json_encode(array(
          `params` => array(
            `width` => '.$w.',
            `height` => '.$h.'
          ),
          `flashvars` => array(
            `file` => str_replace(`./`, `/`, $v),
            `image` => str_replace(`./`, `/`, File::reext($v, `jpg`)),
            `title` => $o->items[$id][`title`],
            `provider` => `http`
          )
        ))'.
        '.`</div></a>`';
      */
    } else {
      $this->ddddByType['video'] =
        '`
<div id="video_`.$id.`"></div>
<script type="text/javascript">
Ngn.video({
"container": $("video_`.$id.`"),
"width": '.($this->w).',
"height": '.$this->h.'
},{
"file": "../../../`.str_replace(`./`, ``, $v).`",
"image": "../../../`.str_replace(`./`, `/`, File::reext($v, `jpg`)).`",
"provider": "http"
});
</script>
`';
      
      /*
       *
       //flowplayer
      $this->ddddByType['video'] = '
      `<a
	href="http://pseudo01.hddn.com/vod/demo.flowplayervod/flowplayer-700.flv"
	style="display:block;width:425px;height:300px;"
	id="player`.$id.`">asd
</a>

<script language="JavaScript">
window.addEvent(`domready`, function(){
flowplayer("player`.$id.`", "/i/js/flowplayer/flowplayer-3.2.7.swf");
});
</script>

`';
*/
    }
    
    $this->ddddByType['flash'] = '
    Tt::getTpl(`common/flash`, array(
      `id` => `flash`.$id,
      `path` => UPLOAD_DIR.`/`.$v[`path`],
      `width` => $v[`w`],
      `height` => $v[`h`]
    ))
    ';
    
    /*
    $this->ddddByType['sound'] = 'empty($v) ? `` : Tt::getTpl(`common/mooSound`, array(
      `id` => `sound`.$id,
      `path` => `./i/swf/mp/player.swf`,
      `width` => 200,
      `height` => 20,
      `flashvars` => array(
        `file` => `../../../`.str_replace(`./`, ``, $v)
      ),
      `strName` => $o->strName,
      `itemId` => $id
    )).`<p><a href="`.$v.`" class="dgray">Скачать (`.File::format2($fSize).`)</a></p>`';
    */
    
    $this->ddddByType['sound'] =
'empty($v) ? `` : `<b class="title">`.$title.`:</b> <div class="mp3player">`.Tt::getTpl(`common/mp3player`, array(`file` => $v)).`</div><div class="mp3download iconsSet"><a href="`.$v.`" class="dgray file"><i></i>Скачать (`.File::format2($fSize).`)</a></div><div class="clear"><!-- --></div>`';
    
  }
  
  protected function initOutputMethodsTpls() {
    $outputMethod = $this->oSettings->getOutputMethod();
    if (!isset($outputMethod[$this->layoutName])) return;
    foreach ($outputMethod[$this->layoutName] as $fieldName => $method) {
      if (empty($this->fields[$fieldName])) {
        // Если output-метдо существует, но поле не должно выводиться
        continue;
      }
      $oMethods = DdoMethods::getInstance();
      $fieldType = $this->fields[$fieldName]['type'];
      if ($method == 'notitle') {
        $this->ddddByName[$fieldName] =
          preg_replace('/<b class="title">[^<]+<\/b>\s*(.*)/', '$1',
            $this->ddddByType[$fieldType] ?: $this->ddddDefault);
        continue;
      }
      if (isset($oMethods->field[$fieldType][$method]['dddd']))
        $this->ddddByName[$fieldName] = $oMethods->field[$fieldType][$method]['dddd'];
      elseif ($oMethods->field[$fieldType][$method]['tpl'])
        $this->tplPathByName[$fieldName] = $oMethods->field[$fieldType][$method]['tpl'];
    }
  }

  public $ddddByType = array(
    //'text' => 'Misc::cut($v, 100)',
    'wisiwig' => 'Misc::cut($v, 100)', 
    'wisiwigSimple' => 'Misc::cut($v, 100)',
    'typoTextarea' => 'nl2br($v)',
    'header' => '', 
    'urls' => '`<ul>`.urls($v, "\n").`</ul>`',
    'file' => '$v ? `<a href="`.$v.`" />Скачать (`.File::format2($fSize).`)</a>` : ``',
    //'image' => '$v ? `<a href="`.Tt::getPath(0).`/`.$pagePath.`/`.$id.`" class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `sm_`, `jpg`).`" /></a>` : ``',
    'imagePreview' => '$v ? `<div class="thumbCont"><a href="`.Tt::getPath(0).`/`.$pagePath.`/`.$id.`" class="thumb"><img src="`.Misc::getFilePrefexedPath($v, `sm_`, `jpg`).`" /></a></div>` : `<div class="thumbCont"></div>`',
    //'ddTags' => 'Tt::enumDddd($v, `<a href="`.Tt::getPath(0).`/`.$pagePath.`/`.$id.`">`.$title.`</a>`)',
    'ddTagsMultiselect' => 'Tt::enumDddd($v, `$title`)',
    //'ddTagsSelect' => 'getPrr($v)',
    'ddTagsSelect' => '$v ? `<b class="title">`.$title.`:</b> <a href="`.Tt::getPath(0).$pagePath.`/t2.`.$v[`groupName`].`.`.$v[`name`].`" class="dgray">`.$v[`title`].`</a>` : ``',
    //DdTagsHtml::treeArrowsNode(Arr::last($v), `<a href="`.Tt::getPath(0).$pagePath.`/t2.$groupName.$name">$title</a>`)
    //'tagsTreeSelect' => '`<b class="title">`.$title.`:</b> `.DdTagsHtml::treeArrows2(array(`pagePath` => $pagePath, `tags` => $v))', // выводим только последний
    'ddTagsTreeSelect' => '`<span class="dgray"><b class="title">`.$title.`:</b> `.DdTagsHtml::treeArrowsLinks(array(`pagePath` => $pagePath, `tags` => $v)).`</span>`', // выводим только последний
    'ddTagsConsecutiveSelect' => 'DdTagsHtml::treeArrowsLinks(array(`pagePath` => $pagePath, `tags` => $v))',
    //'tagsTreeSelect' => 'getPrr($v)',
    //'tagsTreeMultiselect' => '`==`.$pagePath.`==`',
    'ddTagsTreeMultiselect' => '`<ul>`.DdTagsHtml::treeArrows3(array(`pagePath` => $pagePath, `tags` => $v)).`</ul>`',
    'ddCity' => '($v and ($v = DdTagsHtml::lastInBranch($v[0]))) ? `<b class="title">`.$title.`:</b> <`.($pagePath ? `a href="`.DdTags::getLink($pagePath, $v).`"` : `span`).` class="dgray"><img src="/i/img/icons/city.gif" class="icon18" />`.$v[`title`].`</`.($pagePath ? `a` : `span`).`>` : ``',
    'date' => 'date_reformat($v, `d.m.Y`, `Y-m-d`)',
    'datetime' => 'datetimeStrSql($v)',
    'select' => '`<a href="`.Tt::getPath(0).$pagePath.`/v.`.$name.`.`.$v[`k`].`">`.$v[`v`].`</a>`',
    'radio' => '`<a href="`.Tt::getPath(0).$pagePath.`/v.`.$name.`.`.$v[`k`].`">`.$v[`v`].`</a>`',
    'author' => '`<b class="title">`.$title.`:</b> <a href="`.Tt::getUserPath($authorId).`" class="dgray">`.$authorLogin.`</a>`',
    //'ddItemsSelect' => '`<a href="`.$v[`pagePath`].`/`.$v[`id`].`">`.$v[`title`].`</a>`', - for DdoSite
    'ddItemsSelect' => '`<b>`.$v[`title`].`</b>`',
    'static' => '$title',
    'user' => '`<a href="`.Tt::getUserPath($authorId).`">`.$authorLogin.`</a>`',
    'price' => '$v ? $v.` руб.` : ``',
    'phone' => '$v ? `<span class="icon18"><img src="/i/img/icons/phone.png" /></span>`.$v : ``',
    'icq' => '$v ? `<span class="icon18"><img src="http://status.icq.com/online.gif?icq=`.$v.`&img=5" alt="Статус ICQ" /></span>`.$v : ``',
    'skype' => '$v ? `<a href="skype:`.$v.`?call" class="dgray"><img src="/i/img/icons/skype.gif" class="icon18" />`.$v.`</a>` : ``',
    'url' => '$v ? `<a href="`.$v.`" target="_blank" class="dgray"><img src="http://www.google.com/s2/favicons?domain=`.Misc::getHost($v).`" class="icon18" />`.Misc::cut(clearUrl($v), 22).`</a>` : ``',
  );
  
  public $ssssByType = array();
  public $ddddByName = array(
    'rating' => '`<div class="ddRating`.($o->items[$id][`canRate`] ? ` canRate` : ``).`" id="ddRating`.$id.`">`.(int)$v.`</div><div class="clear"><!-- --></div>`',
    'rating_average' => '`<b class="title">Средний рейтинг:</b> `.$v',
  );
  public $ddddDefault = '$v ? `<b class="title">`.$title.`:</b> `.$v : ``';
  public $tplPathByType = array(
    'ddTags' => 'dd/tagsList'
  );
  public $tplPathByName = array();
  
  protected function _html($data) {
    $data['ddddItemLink'] = St::dddd($data['ddddItemLink'], $data);
    $ddddByType = array_merge($this->ddddByType, self::$_ddddByType);
    $ddddByName = array_merge($this->ddddByName, self::$_ddddByName);
    if (isset(self::$funcByName[$data['name']])) {
      $func = self::$funcByName[$data['name']];
      try {
        $r =
          ($this->debug ? 'funcByName:'.$data['name'].'=' : ''). // debug
          $func($data);
      } catch (NgnException $e) {
        throw new NgnException('funcByName name="'.$data['name'].'" error: '.$e->getMessage());
      }
      return $r;
    } elseif (isset($ddddByName[$data['name']])) {
      try {
        $r =
          ($this->debug ? 'ddddByName:'.$data['name'].'=' : ''). // debug
          St::dddd($ddddByName[$data['name']], $data);
      } catch (NgnException $e) {
        throw new NgnException('ddddByName name="'.$data['name'].'" error: '.$e->getMessage());
      }
      return $r;
    } elseif (isset($this->d[$data['type']])) {
    } elseif (isset($this->tplPathByName[$data['name']])) {
      return
        ($this->debug ? 'tplPathByName:name:'.$data['name'] : ''). // debug
        Tt::getTpl($this->tplPathByName[$data['name']], $data);
    } elseif (isset($this->tplPathByType[$data['type']])) {
      return
        ($this->debug ? 'tplPathByType:type:'.$this->tplPathByType[$data['type']].'=' : ''). // debug
        Tt::getTpl($this->tplPathByType[$data['type']], $data);
    } elseif (isset($this->ssssByType[$data['type']])) {
      try {
        $r =
          ($this->debug ? 'ssssByType:'.$data['type'].'=' : ''). // debug
          St::ssss($this->ssssByType[$data['type']], $data);
      } catch (NgnException $e) {
        throw new NgnException('ssssByType type="'.$data['type'].', name="'.$data['name'].'", current class='.get_class($this).'". error: '.$e->getMessage());
      }
      return $r;
    } elseif (isset($ddddByType[$data['type']])) {
      try {
        $r =
          ($this->debug ? 'ddddByType:'.$data['type'].'=' : ''). // debug
          St::dddd($ddddByType[$data['type']], $data);
      } catch (NgnException $e) {
        throw new NgnException('ddddByType type="'.$data['type'].', name="'.$data['name'].'" current class='.get_class($this).'". error: '.$e->getMessage());
      }
      return $r;
    } else {
      return 
        ($this->debug ? 'ddddDefault (type='.$data['type'].'): ' : ''). // debug
        St::dddd($this->ddddDefault, $data);
    }
  }
  
  protected function html($data) {
    $html = $this->_html($data);
    if ($this->page->getS('ownerMode') != 'userGroup') return $html;
    return Html::subDomainLinks($html, DbModelCore::get('userGroup', $data['userGroupId'])->r['name']);
  }
  
  // ------------- Element -------------- 
  
  /**
   * Возвращает HTML элемента DD-записи
   *
   * @param   array   Значение элемента записи
   * @param   array   Имя поля
   * @param   array   ID записи
   * @return  string  HTML
   */
  public function el($value, $fieldName, $itemId) {
    if (!isset($this->fields))
      throw new NgnException('$this->fields not defined. User setItem() or setItems() before');
    if ($itemId) { // Если $itemId != null
      $item = $this->items[$itemId];
      if (!isset($item)) throw new NgnException("No data for item ID=$itemId. Items: ".getPrr($this->items));
    }
    if (isset($item[$fieldName.'_f'])) $value = $item[$fieldName.'_f'];
    $f = $this->fields[$fieldName];
    if (empty($f)) throw new NgnException("No field for field name=$fieldName. Fields:".getPrr($this->fields));
    $tplData = array(
      'pagePath' => isset($this->pagePath) ? $this->pagePath : $this->page['path'],
      'id' => $itemId,
      'type' => $f['type'], 
      'title' => $f['title'], 
      'name' => $f['name'],
      'ddddItemLink' => $this->ddddItemLink,
      'authorId' => $item['authorId'], 
      'authorLogin' => $item['authorLogin'], 
      'authorName' => $item['authorName'],
      'userGroupId' => $item['userGroupId'],
      'v' => $value,
      'o' => $this
    );
    if (FieldCore::hasAncestor($f['type'], 'file')) {
      if (isset($item[$fieldName.'_fSize']))
        $tplData['fSize'] = $item[$fieldName.'_fSize'];
    }
    return
      ($this->debug ? "\n\n<!-- Field=$fieldName, Value=$value. Current Ddo class: ".get_class($this)." -->\n\n" : '').
      $this->html($tplData);
  }
  
  // ------------- Elements -------------- 
  
  public $ddddItemsBegin = '`<div class="items ddItems str_`.$strName.`">`';
  public $tplPathItem = 'dd/elements';
  public $ddddItemsEnd = '`</div><!-- Конец цикла вывода записей по списку полей структуры "`.$strName.`" -->`';
  public $canEdit = false;
  public $premoder = false;
  
  public $elBeginDddd = '`<div class="element f_`.$name.` t_`.$type.`">`';
  public $elEnd = '</div>';
  
  public function itemsBegin() {
    return
      SFLM::getCssTag(SFLM::getCachedUrl(
        's2/css/common/ddItemWidth.css?pageId='.$this->page['id'])).
      St::dddd($this->ddddItemsBegin, array('strName' => $this->page['strName']));
  }
  
  public function itemsEnd() {
    return St::dddd($this->ddddItemsEnd, array('strName' => $this->page['strName']));
  }
  
  public function els() {
    if (!isset($this->fields))
      throw new NgnException('$this->fields not defined. User setItem() or setItems() before');
    if ($this->debug) print 'class='.get_class($this);
    $html = '<!-- Начало цикла вывода записей по списку элементов структуры "'.$this->strName.'" -->'."\n";
    $html .= $this->itemsBegin();
    foreach ($this->items as $v) $html .= $this->elsItem($v);
    $html .= $this->itemsEnd();
    return $html;
  }
  
  public function elsSeparate() {
    Err::noticeSwitch(false);
    $html = array();
    foreach ($this->items as $v) $html[$v['id']] = $this->elsItem($v);
    Err::noticeSwitchBefore();
    return $html;
  }
  
  protected function elsItem(array &$item) {
    $item['o'] = $this;
    $v['premoder'] = $this->premoder;
    $s = Tt::getTpl($this->tplPathItem, $item);
    if ($this->page['settings']['mysite']) $s = Misc::extendSubdomain($s, $item['authorName']);
    return $s;
  }
  
  // --------------------------------------
  
  static public function getDdLOClassNames() {
    return Arr::filter_and_replace_by_regexp(
      Arr::get(Lib::getClassesListCached(), 'file'),
      '/(Ddo.*)\.class\.php/'
    );
  }
  
  // --------------------------------------
  
  static public function getFlatValue($v) {
    if (is_array($v)) {
      if (isset($v['name']))
        return $v['name'];
    } else return $v;
  }
  
  // --------------------------------------
  
  static protected $_ddddByType = array();
  
  static public function addDdddByType($type, $dddd) {
    self::$_ddddByType[$type] = $dddd;
  }
  
  static protected $_ddddByName = array();
  
  static public function addDdddByName($name, $dddd) {
    self::$_ddddByName[$name] = $dddd;
  }
  
  static public $funcByName = array();
  
  static public function addFuncByName($name, Closure $func) {
    self::$funcByName[$name] = $func;
  }

  // global space for something
  static public $g;

}
