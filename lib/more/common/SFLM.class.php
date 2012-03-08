<?php

/**
 * Static File Librarys Manager
 *
 * Имя CSS и JS библиотек используется в качестве siteSet'а.
 * Т.е. для siteSet'а "personal" будет подключены библиотеки с именем "personal"
 */
class SFLM {

  const DYNAMIC_LIB_NAME = 'dynamic';
  
  static public $cssLibs = array(
    // ---------- Main libs -------------
    self::DYNAMIC_LIB_NAME => array(),
    'common' => array(
      'i/css/common.css',
      'i/css/common/btns.css',
      'i/css/common/pageBlocks.css',
      'i/css/common/dialog.css',
      'i/css/common/items.css',
      'i/css/common/form.css',
      'i/css/common/fancy.css',
      'i/css/mooRainbow/small.css',
      's2/css/common/icons.css',
      's2/css/common/smIcons.css',
      'i/css/common/resizableTextarea.css',
      'i/css/common/interface.css',
      'i/css/milkbox/milkbox.css',
    ),
    'form' => array(
      'i/css/datepicker/cp.css',
    ),
    'content' => array(
      'i/css/common/text.css',
      'm/css/common/text.css'
    ),
    'cp' => array(
      'common',
      'i/css/cp/main.css',
      'i/css/cp/form.css',
      'form'
    ),
    'adminExtra' => array(
      's2/css/common/pageModules',
      'i/css/common/miftree.css', // необходим для кнопки добавления ссылки на раздел
    ),
    'admin' => array(
      'cp',
      'i/css/common/ac.css',
      'i/css/admin/pageBlocks.css',
      'i/css/common/sound.css',
      'i/css/common/adminPanel.css',
      'adminExtra'
    ),
    'site' => array(
      'common',
      'adminExtra',
      'i/css/common/screen.css',
      'content',
      'i/css/common/grid.css',
      'i/css/common/design.css', // должен польностью воспроизводиться с помощью модуля тем
      'i/css/common/pageModules.css',
      //'s2/css/common/horizontalMenu.css?ids=menu,orderMenu',
      'i/css/common/hMenu.css',
      'i/css/common/msgs.css',
      'i/css/common/ddCalendar.css',
      //'i/js/ac/Autocompleter.css',
      'i/css/common/adminPanel.css',
      'i/css/common/frontbar.css',
      'm/css/common/design.css',
      'm/css/common/text.css',
      's2/css/common/avatar.css',
      'textboxList',
      's2/css/common/floatBlock.css',
      'form',
      'i/css/common/sound.css',
      'i/css/common/briefcase.css',
      'i/css/common/userGroup.css',
      'i/css/common/treeTagsDialog.css'
    ),
    // ----------- End of main libs --------------
    'jdGallery' => array(
      'i/js/jd/css/jd.gallery.css'
    ),
    'portal-common' => array(
      'site',
      'i/css/common/ac.css',
      'i/css/common/text.css',
      'i/css/site/pageBlocks.css',
      'i/css/common/privMsgsSimple.css',
      'i/css/portal/common.css',
      //'s2/css/common/roundedMenu.css',
    ),
    'no-auth' => array(
    ),
    'portal' => array(
      'portal-common',
      //'i/css/common/colors.css',
      //'s2/css/common/colors.css',
    ),
    'portal-nocolors' => array(
      'portal-common'
    ),
    'portal-lgray' => array(
      'portal-common',
      'i/css/portal/lgray-colors.css',
    ),
    'portal-black' => array(
      'portal-common',
      'i/css/black/icons.css',
      'i/css/black/design.css',
      'i/css/black/dialog.css',
    ),
    'personal' => array(
      'site',
      'i/css/personal/common.css',
    ),
    'le' => array('i/css/common/le.css'),
    'textboxList' => array(
      'i/js/textboxList/textboxList.css',
      'i/js/textboxList/textboxList.Autocomplete.css'
    ),
    'tinyContent' => array(
      'i/css/common/screen.css',
      'i/css/common/text.css',
      'i/css/common/tiny.css',
      's2/css/common/theme'
    ),
    'tinyPageBlocks' => array(
      'i/css/common/screen.css',
      'i/css/common/text.css',
      'i/css/common/tiny.css',
      's2/css/common/theme',
      's2/css/common/tiny.pageBlocks.css',
    )
  );
  
  static public $jsLibs = array(
    // 3 файла - это уже библиотека
    'core' => array(
      'i/js/mootools/mootools-core-1.4.0.js',
      'i/js/mootools/mootools-more-1.4.0.1.js',
      'i/js/ngn/Ngn.js',
      's2/js/common/Ngn.js',
      'i/js/ngn/Ngn.frm.js',
      //'i/js/ngn/Ngn.frm.FieldSet.js',
    ),
    'common' => array(
      'core',
      'i/js/ngn/Ngn.Request.js',
      'i/js/ngn/Ngn.AdminPanel.js',
      'i/js/phpFunctions.js',
      'i/js/ngn/Ngn.InputDefaultText.js',
      'i/js/ngn/Ngn.DdCalendar.js',
      'i/js/ngn/Ngn.HorizontalMenu.js',
      'i/js/ngn/Ngn.HorizontalMenuRounded.js',
      'lightbox',
      'dialogs',
      'tiny',
      'i/js/swfobject.js',
      'upload',
      'ddForm',  
      'i/js/AutoGrow.js',
      //'i/js/Date.js',
      'i/js/ngn/Ngn.ResizableTextarea.js',
      'i/js/ngn/Ngn.ResizableWisiwig.js',
      'i/js/mooRainbow.js',
      'i/js/ngn/Ngn.UlMenu.js',
      'i/js/ngn/Ngn.Select.js',
      'i/js/ngn/Ngn.Tips.js',
      's2/js/common/tpl?name=editBlock&path=editBlocks/jsTpl.editBlock'
    ),
    'lightbox' => array(
      'i/js/milkbox.js',
      'i/js/ngn/Ngn.lightbox.js'
    ),
    'ac' => array(
      'i/js/ac/Observer.js',
      'i/js/ac/Autocompleter.js',
      'i/js/ac/Autocompleter.Local.js',
      'i/js/ac/Autocompleter.Request.js',
      'i/js/ngn/Ngn.Autocompleter.js',
      'i/js/ngn/Ngn.Autocompleters.js'
    ),
    'jdGallery' => array(
      'i/js/jd/js/jd.gallery.js',
      'i/js/jd/js/jd.gallery.set.js',
      'i/js/jd/js/jd.gallery.transitions.js',
      'i/js/jd/js/History.js',
      'i/js/jd/js/History.Routing.js',
      'i/js/jd/js/ReMooz.js',
      'i/js/ngn/Ngn.FullScreenGallery.js',
    ),
    'cp' => array(
      'common',
      'ac',
      'i/js/ngn/Ngn.HidebleBar.js',
      'i/js/ngn/Ngn.cp.js',
      'i/js/ngn/Ngn.Items.js',
      'i/js/ngn/Ngn.ItemsTable.js',
      'i/js/ngn/Ngn.cp.TwoPanels.js',
      'i/js/ngn/Ngn.cp.ddFieldType.js'
    ),
    'treeEdit' => array(
      'mif.tree',
      'i/js/ngn/Ngn.TreeStateStorage.js',
      'i/js/ngn/Ngn.TreeEdit.js',
    ),
    'partialJob' => array(
      'i/js/ngn/Ngn.PartialJob.js',
      'i/js/ngn/Ngn.PartialJob.Dialog.js',
      'i/js/ngn/Ngn.PartialJob.Parallel.js',
    ),
    'admin' => array(
      'cp',
      //'s2/js/common/submitTitles.js',
      'i/js/ngn/Ngn.IconBtn.js',
      'treeEdit',
      //'i/js/ngn/Ngn.ContextMenu.js',
      //'i/js/ngn/Ngn.ContextMenuPages.js',
      'i/js/ngn/Ngn.RequestFieldsSelect.js',
      'i/js/ngn/Ngn.Dialog.NewPage.js',
      'i/js/ngn/Ngn.TreeEditPages.js',
      'i/js/ngn/Ngn.TreeEditTags.js',
      //'i/js/ngn/Ngn.ContextMenu.js',
      //'i/js/firebug.js',
      'i/js/ngn/Ngn.ItemsTablePages.js',
      'i/js/ngn/Ngn.cp.DdItemsTable.js',
      'i/js/ngn/Ngn.cp.DdItemsGroup.js',
      'i/js/ngn/Ngn.cp.ResizeImages.js',
      //'i/js/ngn/Ngn.FieldSetEdit.js',
      //'i/js/ngn/Ngn.FieldSetsEdit.js',
      'i/js/ngn/Ngn.initConfigManager.js',
      'i/js/MooCountdown.js',
      'partialJob',
      'pageBlocks'
    ),
    'pageLink' => array(
      'i/js/ngn/Ngn.DropdownWin.js',
      'treeEdit',
      'i/js/ngn/Ngn.TreeEditPages.js',
      'i/js/ngn/Ngn.frm.Page.js'
    ),
    'form' => array(
      'i/js/ngn/Ngn.Form.js',
      'i/js/ngn/Ngn.IframeFormRequest.js',
      'i/js/ngn/Ngn.frm.js',
      'i/js/ngn/Ngn.IconBtn.js',
      'i/js/ngn/Ngn.frm.FieldSet.js',
      'i/js/ngn/Ngn.frm.Saver.js',
      'i/js/ngn/Ngn.frm.ConsecutiveSelect.js',
      'i/js/ngn/Ngn.frm.maxLength.js',
      'pageLink'
    ),
    'dialogs' => array(
      'i/js/ngn/Ngn.Tabs.js',
      'i/js/ngn/Ngn.Dialog.js',
      'i/js/ngn/Ngn.Dialog.Queue.js',
      'i/js/ngn/Ngn.Dialog.RequestForm.js',
      'i/js/ngn/Ngn.Dialog.Queue.Request.js',
      'i/js/ngn/Ngn.Dialog.Queue.Request.Form.js',
      'i/js/favicon.js',
      'i/js/ngn/Ngn.faviconTimer.js',
      'i/js/ngn/Ngn.Dialog.Loader.js',
      'i/js/favicon.js',
      'i/js/ngn/Ngn.Dialog.Auth.js',
      'i/js/ngn/Ngn.Dialog.Tiny.js',
      'i/js/Dotter.js',
    ),
    'grabber' => array(
      'i/js/ngn/Ngn.PartialJobDialogLoader.js',
      'i/js/ngn/Ngn.GrabberChannelsTable.js',
    ),
    'slices' => array(
      'i/js/Dotter.js',
      'i/js/ngn/Ngn.slice.js',
    ),
    'ratings' => array(
      'i/js/ngn/Ngn.rating.js',
      'i/js/ngn/Ngn.ItemRating.js',
      'i/js/ngn/Ngn.ItemsRating.js',
    ),
    'ddForm' => array(
      'form',
      'i/js/ngn/Ngn.Validator.js',
      'i/js/ngn/Ngn.DdForm.js',
      'i/js/ngn/Ngn.TagsTreeSelect.js',
      'i/js/DatePicker.js',
      'textboxList',
      'datePicker'
    ),
    'datePicker' => array(
      'i/js/picker/Locale.ru-RU.DatePicker.js',
      'i/js/picker/Picker.js',
      'i/js/picker/Picker.Attach.js',
      'i/js/picker/Picker.Date.js',
      'i/js/ngn/Ngn.DatePicker.js',
    ),
    'video' => array(
      'i/js/ngn/Ngn.video.js',
      'i/js/ngn/Ngn.Dialog.Video.js',
      'i/js/ngn/Ngn.videoItems.js',
      //'i/js/flowplayer/flowplayer-3.2.6.min.js',
    ),
    'site' => array(
      'common',
      'i/js/ngn/Ngn.site.js',
      'i/js/ngn/Ngn.layout.js',
      'i/js/ngn/Ngn.SubmitButtons.js',
      'i/js/ngn/Ngn.SliceEdit.js',
      'i/js/ngn/Ngn.SlideTips.js',
      'i/js/ngn/Ngn.SlideTips.Pm.js',
      'i/js/ngn/Ngn.StickyFooter.js',
      'i/js/ngn/Ngn.cart.js',
      'ac',
      'ratings',
      'video',
      //'sound',
      'atlas',
      'msgs',
      'site.ddItems',
      'i/js/ngn/Ngn.site.top.js',
      'i/js/ngn/Ngn.site.top.briefcase.js',
      'm/js/site.js',
      'i/js/ngn/Ngn.BlockEditDialog.js',
      'i/js/ngn/Ngn.EditTreeTagsDialog.js',
      'site.userGroup',
      'pageBlocks'
    ),
    'site.userGroup' => array(
      'i/js/ngn/Ngn.TreeEditTags.js',
      'i/js/ngn/Ngn.site.userGroup.js',
    ),
    'site.ddItems' => array(
      'i/js/ngn/Ngn.site.DdItems.js',
    ),
    'carousel' => array(
      'i/js/ngn/Ngn.Carousel.js',
      'i/js/ngn/Ngn.Carousel.Loop.js',
      'i/js/ngn/Ngn.Carousel.Select.js',
    ),
    'msgs' => array(
      'i/js/ngn/Ngn.msgs.js',
      'i/js/ngn/Ngn.msgs.Actions.js',
      'i/js/ngn/Ngn.msgs.AnswerBlock.js',
      'i/js/ngn/Ngn.msgs.EditLayout.js',
      'i/js/ngn/Ngn.msgs.MsgLayout.js',
      'i/js/ngn/Ngn.msgs.MsgsLayout.js'
    ),
    'portal' => array(
      'site',
      'i/js/ngn/Ngn.portal.js',
      'i/js/ngn/Ngn.horizontalMenu.js',
      'i/js/ngn/Ngn.LostPassForm.js',
      'i/js/ngn/Ngn.cp.js',
      //'i/js/clientcide/Layout/SimpleSlideShow.js',
      'slices',
      'ac',
      //'i/js/MooCountdown.js',
      'i/js/ngn/Ngn.PageBlockSlider.js',
      'i/js/ngn/Ngn.LayoutTile.js',
      'i/js/Rounded.js',
      'i/js/ngn/Ngn.FlagLink.js',
      'i/js/ngn/Ngn.SubscribeLink.js',
    ),
    'personal' => array(
      'site',
      'i/js/ngn/Ngn.cssJsMenu.js',
      'slices',
      'ddForm',
      'i/js/LazyLoad.js',
      'i/js/Zoomer.js',
      'i/js/mootools/Fx.Scroll.Carousel.js',
      'i/js/mootools/Fx.Scroll.Carousel.Loop.js',
    ),
    'tiny' => array(
      's2/js/common/Ngn.TinySettings.js',
      'i/js/ngn/Ngn.TinyInit.js'
    ),
    'mif.tree' => array(
      'i/js/mif.tree/mif.tree-v1.2.6.4.js',
      /*
      'i/js/mif.tree/Core/Mif.Tree.mootools-patch.js',
      'i/js/mif.tree/Core/Mif.Tree.js',
      'i/js/mif.tree/Core/Mif.Tree.Node.js',
      'i/js/mif.tree/Core/Mif.Tree.Selection.js',
      'i/js/mif.tree/Core/Mif.Tree.Draw.js',
      'i/js/mif.tree/Core/Mif.Tree.Hover.js',
      'i/js/mif.tree/Core/Mif.Tree.Load.js',
      'i/js/mif.tree/More/Mif.Tree.Checkbox.js',
      'i/js/mif.tree/More/Mif.Tree.CookieStorage.js',
      'i/js/mif.tree/More/Mif.Tree.Drag.js',
      'i/js/mif.tree/More/Mif.Tree.Drag.Element.js',
      'i/js/mif.tree/More/Mif.Tree.KeyNav.js',
      'i/js/mif.tree/More/Mif.Tree.Rename.js',
      'i/js/mif.tree/More/Mif.Tree.Row.js',
      'i/js/mif.tree/More/Mif.Tree.Sort.js',
      'i/js/mif.tree/More/Mif.Tree.Transform.js',
      */
    ),
    'sound' => array(
      'i/js/ngn/Ngn.Sound.js'
    ),
    'upload' => array(
      'i/js/fancy/Fx.ProgressBar.js',
      'i/js/fancy/Swiff.Uploader.js',
      'i/js/fancy/FancyUpload3.Attach.js',
      'i/js/ngn/Ngn.UploadAttach.js'
    ),
    'atlas' => array(
      'i/js/Atlas.js',
      'i/js/ngn/Ngn.Atlas.js',
      //'s2/js/common/Ngn.googleMapKey.js',
      //'i/js/ngn/Ngn.GoogleMap.js'
    ),
    // LayoutEditor
    'le' => array(
      'i/js/ngn/le/Ngn.LayoutMenu.js',
      'i/js/ngn/le/Ngn.LayoutEditor.js',
    ),
    'mootools12' => array('i/js/textboxList/mootools-1.2.1-core-yc.js'),
    'textboxList' => array(
      'i/js/textboxList/GrowingInput.js',
      'i/js/textboxList/TextboxList.js',
      'i/js/textboxList/TextboxList.Autocomplete.js',
      'i/js/textboxList/TextboxList.Autocomplete.Binary.js',
    ),
    'pageBlocks' => array(
      'i/js/ngn/Ngn.PageBlockCreate.js',
      'i/js/ngn/Ngn.PageBlocksEdit.js',
    )
  );
  
  static public $debug = false;
  
  static public $forceCache = false;
  
  static public $jsBaseDirs;
  
  static public $cssBaseDirs;
  
  static public $implode = true;
  
  private static function setJsLibs() {
    if (isset(self::$jsBaseDirs))
      return;
    self::$jsBaseDirs = array(
      array(
        'root' => WEBROOT_PATH.'/i/js/', 
        'webroot' => '../../../i/js/'
      ), 
      array(
        'root' => LIB_PATH.'/more/scripts/scripts_noDb/js/', 
        'webroot' => '../../../s2/js/'
      )
    );  
  }
  
  static public function init() {
    throw new Exception('Плохо!!!');
    // sflm используется не только там где нужны темы
    if (Config::getVarVar('theme', 'enabled'))
      self::$cssLibs['tinyContent'][] = 's2/css/common/theme';
  }
  
  static public function addJsLib($libName, $libPath) {
    self::$jsLibs[$libName][] = $libPath;
  }
  
  static public function addCssLib($libName, $libPath) {
    if (in_array($libPath, self::$cssLibs[$libName])) return;
    self::$cssLibs[$libName][] = $libPath;
  }
  
  static public function removeCssLib($theme, $lib) {
    Arr::remove(self::$cssLibs[$theme], $lib);
  }
    
  static public function getLibs() {
    self::setJsLibs();
    foreach (self::$jsBaseDirs as $v) {
      foreach (Dir::dirs($v['root']) as $libName) {
        if (Dir::isDirs($v['root'] . '/' . $libName)) {
          $dirs = Dir::dirs($v['root'] . '/' . $libName);
          foreach ($dirs as $subDir) {
            foreach (Dir::files(
              $v['root'] . '/' . $libName . '/' . $subDir) as $file) {
              if (!strstr($file, '.js')) continue;
              $file = str_replace('.php', '', $file);
              $libs[$v['webroot']][$libName][] = $subDir . '/' . $file;
            }
          }
        } else {
          foreach (Dir::files($v['root'] . '/' . $libName) as $file) {
            if (!strstr($file, '.js')) continue;
            $file = str_replace('.php', '', $file);
            $libs[$v['webroot']][$libName][] = $file;
          }
        }
      }
    }
    return $libs;
  }
  
  /**
   * Перебирает список базовых директорий. Каждая субдиректория из этого списка 
   * воспринимается как отдельная библиотека. Содержимое её файлов и файлов её
   * субдиректорий склеивается и помещается с массив, который возвращает эта функция.
   * библиотека
   *
   * @return  array   Массив, где каждый элемент - это библиотека со статическим кодом 
   */
  private static function getJsLibsSplited() {
    self::setJsLibs();
    foreach (self::$jsBaseDirs as $v) {
      foreach (Dir::dirs($v['root']) as $libName) {
        $libs[$libName] = '';
        if (Dir::isDirs($v['root'] . $libName)) {
          $dirs = Dir::dirs($v['root'] . $libName);
          foreach ($dirs as $subDir) {
            foreach (Dir::files(
              $v['root'] . $libName . '/' . $subDir) as $file) {
              if (!strstr($file, '.js')) continue;
              $libs[$libName] .= self::getFileContents($v['root'] . $libName . '/' . 
                $subDir . '/' . $file);
            }
          }
        } else {
          foreach (Dir::files($v['root'] . $libName) as $file) {
            if (!strstr($file, '.js')) continue;
              $libs[$libName] .= self::getFileContents($v['root'] . $libName . '/' . $file);
          }
        }
      }
    }
    return $libs;
  }
  
  private static function getFileContents($path, $r = array()) {
    if (!is_file($path)) {
      return "\n/*----------[ File '$path' does not exists ]---------*/\n";
    }
    if (strstr($path, LIB_PATH)) {
      // Если файл находится в папке библиотек, значит это PHP-файл
      return "\n/*----------|$path|".($r ? ' (with request data)' : '')."----------*/\n".
        Misc::getIncludedByRequest($path, $r);
    } else {
      // Иначе это статика
      return "\n/*----------|$path|----------*/\n".
        file_get_contents($path);
    }
  }
  
  static protected $stored;
  
  // нельзя позволить ф-ии выполняться дважды
  static public function storeAllJsLibs() {
    if (isset(self::$stored)) return;
    self::$stored = true;
    foreach (self::$jsLibs as $libName => $v) {
      self::storeJsLib($libName);
    }
  }
  
  /*
  protected static $dynamicLibsInitialized = false;
  
  protected static function initDynamicLibs() {
    if (self::$dynamicLibsInitialized) return;
    foreach (ClassCore::getDescendants('Dgsfl') as $v) {
      $o = O::get($v['class']);
      if (!$o->enabled()) continue;
      if (($path = $o->getJsPath()) !== false) self::$jsLibs[$v['name']] = $path;
      if (($path = $o->getCssPath()) !== false) self::$cssLibs[$v['name']] = $path;
    }
    self::$dynamicLibsInitialized = true;
  }
  */
  
  protected static function getCssLibCode($libName) {
    if (!isset(self::$cssLibs[$libName]))
      throw new NgnException("CSS lib '$libName' not found");
    $cssCode = '';
    Err::noticeSwitch(false); // Выключаем отображение нотисов
    //self::initDynamicLibs();
    foreach (self::$cssLibs[$libName] as $path) {
      if (strstr($path, '/print.css')) continue;
      if (self::isLibPath($path)) {
        // Если это не файл, значит это библиотека
        $cssCode .= self::getCssLibCode($path);
        continue;
      }
      $absPath = self::getAbsPath($path);
      $p = parse_url($path);
      if (!empty($p['query'])) {
        $a = array();
        parse_str($p['query'], $a);
        $code = self::getFileContents($absPath, $a);
      } else {
        $code = self::getFileContents($absPath);
      }
      $code = preg_replace('/^\@CHARSET.*$/im', '', $code);
      $cssCode .= $code;
    }
    Err::noticeSwitchBefore(); // Включаем отображение нотисов
    return $cssCode;
  }
  
  private static function storeCssLib($libName) {
    Dir::make(UPLOAD_PATH.'/css/sub');
    file_put_contents(UPLOAD_PATH."/css/sub/$libName.css", self::getCssLibCode($libName));
  }
  
  private static function isLibPath($path) {
    return !strstr($path, '/');
  }
  
  static protected $jsPathsAdded;
  
  static public function getJsLibCode($libName) {
    self::$jsPathsAdded = array();
    return self::_getJsLibCode($libName);
  }
  
  static protected function _getJsLibCode($libName) {
    if (!isset(self::$jsLibs[$libName]))
      throw new NgnException("JS lib '$libName' not found in array self::\$jsLibs");
    $jsCode = '';
    Err::noticeSwitch(false); // Выключаем отображение нотисов
    //self::initDynamicLibs();
    foreach (self::$jsLibs[$libName] as $path) {
      if (self::isLibPath($path)) {
        // Если это не файл, значит это библиотека
        $jsCode .= self::_getJsLibCode($path);
        continue;
      }
      if (in_array($path, self::$jsPathsAdded)) continue;
      $absPath = self::getAbsPath($path);
      self::$jsPathsAdded[] = $path;
      $p = parse_url($path);
      if (!empty($p['query'])) {
        $a = array();
        parse_str($p['query'], $a);
        $jsCode .= self::getFileContents($absPath, $a);
      } else {
        $jsCode .= self::getFileContents($absPath);
      }
    }
    Err::noticeSwitchBefore(); // Включаем отображение нотисов
    return $jsCode;
  }
  
  private static function storeJsLib($libName) {
    Dir::make(UPLOAD_PATH.'/js/sub');
    file_put_contents(UPLOAD_PATH."/js/sub/$libName.js", self::getJsLibCode($libName));
  }
  
  private static function getAbsPath($path) {
    $p = parse_url($path);
    $path = $p['path'];
    if (preg_match('/^[mu]\/.*/', $path)) {
      return WEBROOT_PATH.'/'.$path;
    } elseif (preg_match('/^i\/.*/', $path)) {
      return file_exists(NGN_PATH.'/'.$path) ? NGN_PATH.'/'.$path : WEBROOT_PATH.'/'.$path;
    } else {
      return Misc::getScriptPath($path);
    }
  }
  
  static public function getCssUrl($libName) {
    if (self::$debug or self::$forceCache or !file_exists(UPLOAD_PATH.'/css/sub/'.$libName.'.css')) {
      // Если идёт отладка статических файлов или собранного файла не существует
      self::storeCssLib($libName);
    }
    return UPLOAD_DIR.'/css/sub/'.$libName.'.css';
  }
  
  static public function getCssCodeInTag($libName) {
    return "<style>\n".self::getCssCode($libName)."\n</style>";
  }
  
  static public function getCssCode($libName) {
    if (
    self::$debug or 
    self::$forceCache or 
    !file_exists(UPLOAD_PATH.'/css/sub/'.$libName.'.css')
    ) {
      // Если идёт отладка статических файлов или собранного файла не существует
      self::storeCssLib($libName);
    }    
    return file_get_contents(UPLOAD_PATH.'/css/sub/'.$libName.'.css');
  }
  
  static public function getJsCode($libName) {
    if (
    self::$debug or 
    self::$forceCache or 
    !file_exists(UPLOAD_PATH.'/js/sub/'.$libName.'.css')
    ) {
      // Если идёт отладка статических файлов или собранного файла не существует
      self::storeJsLib($libName);
    }    
    return file_get_contents(UPLOAD_PATH.'/js/sub/'.$libName.'.js');
  }
  
  static public function getCssTags($libName) {
    if (self::$debug) {
      $t = '';
      if (!isset(self::$cssLibs[$libName]))
        throw new NgnException("CSS lib '$libName'' does not exists");
      foreach (self::$cssLibs[$libName] as $path) {
        if (self::isLibPath($path))
          $t .= self::getCssTags($path);
        else {
          if (file_exists(self::getAbsPath($path)))
            $t .= '<link rel="stylesheet" type="text/css" href="/'.
              $path.'" media="screen, projection" />'."\n";
        }
      }
      return $t;
    } else {
      return self::getCssTag(self::getCssUrl($libName));
    }
  }
  
  static public function getCssTag($path) {
    return '<link rel="stylesheet" type="text/css" href="/'.
      $path.'?'.BUILD.'" media="screen, projection" />'."\n";
  }
  
  static public function getJsUrl($libName) {
    if (self::$debug or self::$forceCache or !file_exists(UPLOAD_PATH.'/js/sub/'.$libName.'.js')) {
      // Если идёт отладка статических файлов или собранного файла не существует
      self::storeJsLib($libName);
    }
    return '/'.UPLOAD_DIR.'/js/sub/'.$libName.'.js';
  }
  
  static public function getJsTags($libName) {
    if (self::$debug) {
      $t = '';
      foreach (self::$jsLibs[$libName] as $path) {
        if (self::isLibPath($path)) {
          $t .= self::getJsTags($path);
        } else {
          $t .= '<script src="'.$path.'" type="text/javascript"></script>'."\n";
        }
      }
      return $t;
    } else {
      return self::getJsTag(self::getJsUrl($libName));
    }
  }
  
  static public function getJsTag($path) {
    return '<script src="'.$path.'?'.BUILD.'" type="text/javascript"></script>'."\n";
  }
  
  static public function makeCachedFile($path) {
    $path2 = str_replace('s2/', '', $path);
    $file = UPLOAD_PATH.'/'.dirname($path2).'/cache/'.basename($path2);
    if (file_exists($file))
      unlink($file);
    // -------------------------------
    return self::getCachedUrl($path);
  }
  
  /**
   * Возвращает ссылку относительно вебрута
   * на скоптанованный и закешированый файл библиотеки
   *
   * @param   string  Имя библиотеки
   * @return  string  Ссылка относительно вебрута
   */
  static public function getJsCachedUrlLib($lib) {
    return self::getCachedUrl('s2/js/common/lib?lib='.$lib);
  }
  
  static protected function getCachePath($path) {
    $path2 = str_replace('s2/', '', $path);
    return (strstr($path2, 'css') ? 'css' : 'js').'/cache/'.basename($path2).
      (strstr($path2, '.') ? '' : (strstr($path2, 'css/') ? '.css' : '.js'));
  }
  
  static public function clearPathCache($path) {
    File::delete(UPLOAD_PATH.'/'.self::getCachePath($path));
  }
  
  static public function getCachedUrl($path, $silent = false) {
    if (DEBUG_STATIC_FILES === true) return $path;
    $p = parse_url($path);
    $path = $p['path'];
    $cachePath = self::getCachePath($path);
    $path = Misc::getScriptPath($path);
    if (FORCE_STATIC_FILES_CACHE === true or !file_exists(UPLOAD_PATH.'/'.$cachePath)) {
      Dir::make(UPLOAD_PATH.'/'.dirname($cachePath));
      if (!empty($p['query'])) {
        parse_str($p['query'], $q);
        if (!empty($q)) {
          $cachePath = Misc::getFilePrefexedPath(
            $cachePath,
            Tt::enum($q, '-', '$k.`,`.$v').'.'
          );
        }
      } else {
        $q = array();
      }
      file_put_contents(
        UPLOAD_PATH.'/'.$cachePath,
        Misc::getIncludedByRequest($path, $q)
      );
    }
    return UPLOAD_DIR.'/'.$cachePath;
  }
  
  static public function clearJsCssCache() {
    Dir::clear(UPLOAD_PATH.'/js');
    Dir::clear(UPLOAD_PATH.'/css');
  }
  
}