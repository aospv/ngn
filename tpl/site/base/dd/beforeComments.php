<?php

if (Config::getVarVar('sape', 'enable')) {
  $conf = Config::getVar('sape');
  if (!defined('_SAPE_USER'))
    define('_SAPE_USER', $conf['code']); 
  $sape = O::get('NgnSapeClient', array(
    'multi_site' => $conf['multiSite'],
    'charset' => CHARSET,
    'verbose' => IS_DEBUG
  ));
  print "<sape_index>\n".$sape->return_links($conf['linksN'])."\n</sape_index>";
}
