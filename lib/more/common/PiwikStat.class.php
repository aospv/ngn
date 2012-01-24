<?php

class PiwikStat {

  public function __construct() {
    $this->conf = Config::getVar('piwik');
  }

  public function enable() {
    $r = $this->api('SitesManager.getSitesIdFromSiteUrl', array(
      'url' => 'http://'.SITE_DOMAIN
    ));
    if ($r) return;
    $siteId = $this->api('SitesManager.addSite', array(
      'siteName' => SITE_TITLE,
      'urls' => 'http://'.SITE_DOMAIN
    ));
    Misc::checkEmpty($siteId);
    SiteConfig::updateSubVar('stat', 'siteId', $siteId);
  }
  
  public function disable() {}
  
  public function api($method, array $params) {
    $params['module'] = 'API';
    $params['method'] = $method;
    $params['token_auth'] = $this->conf['authToken'];
    $params['format'] = 'PHP';
    return unserialize(file_get_contents($this->conf['url'].'?'.http_build_query($params)));
  }

}
