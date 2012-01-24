.home .mif-tree-icon { background-image: url(/i/img/icons/home.png); }
<?

foreach (array_keys(O::get('PageModules')->getItems()) as $module) {
  if (!file_exists(CORE_PAGE_MODULES_PATH.'/'.$module.'/sm-page.png')) continue;
  $pageIcon = '/'.CORE_PAGE_MODULES_DIR.'/'.$module.'/sm-page.png';
  $folderIconClosed = '/'.CORE_PAGE_MODULES_DIR.'/'.$module.'/sm-folder-closed.png';
  $folderIconOpened = '/'.CORE_PAGE_MODULES_DIR.'/'.$module.'/sm-folder-opened.png';
  $homeIcon = '/'.CORE_PAGE_MODULES_DIR.'/'.$module.'/sm-home.png';
  print "
.mif-pm-$module .mif-tree-page-icon { background-image: url($pageIcon) !important; }
.mif-pm-$module .mif-tree-folder-close-icon { background-image: url($folderIconClosed) !important; }
.mif-pm-$module .mif-tree-folder-open-icon { background-image: url($folderIconOpened) !important; }
.mif-pm-$module.home .mif-tree-icon { background-image: url($homeIcon) !important; }
.item-pm-$module .title .page i { background: url($pageIcon) !important; }
.item-pm-$module .title .folder i { background: url($folderIconClosed) !important; }

";
}