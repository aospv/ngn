<?php

class SiteHook {

  static public function getPaths($path, $pageModule = null) {
    $paths = array();
    if (file_exists(NGN_PATH.'/hooks/site/'.$path.'.php'))
      $paths[] = NGN_PATH.'/hooks/site/'.$path.'.php';
    if (file_exists(NGN_PATH.'/hooks/siteSet/'.SITE_SET.'/'.$path.'.php'))
      $paths[] = NGN_PATH.'/hooks/siteSet/'.SITE_SET.'/'.$path.'.php';
    if (file_exists(SITE_PATH.'/hooks/site/'.$path.'.php'))
      $paths[] = SITE_PATH.'/hooks/site/'.$path.'.php';
    if (file_exists(SITE_PATH.'/hooks/siteSet/'.SITE_SET.'/'.$path.'.php'))
      $paths[] = SITE_PATH.'/hooks/siteSet/'.SITE_SET.'/'.$path.'.php';
    if ($pageModule) {
      if (($info = PageModuleCore::getInfo($pageModule)) !== false and
         (($file = $info->getFile('hooks/'.$path)) !== false)) {
        $paths[] = $file;
      }
    }
    return $paths ? $paths : false;
  }

}