<?php

class Hook {

  static public function getPath($path) {
    if (file_exists(SITE_PATH.'/hooks/'.$path.'.php'))
      return SITE_PATH.'/hooks/'.$path.'.php';
    elseif (file_exists(NGN_PATH.'/hooks/siteSet/'.SITE_SET.'/'.$path.'.php'))
      return NGN_PATH.'/hooks/siteSet/'.SITE_SET.'/'.$path.'.php';
    return false;
  }

}