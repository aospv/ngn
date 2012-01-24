<?php

class SiteRequest {

  /**
   * @var Req
   */
  protected $req;

  public function __construct(Req $req = null) {
    $this->req = $req ? $req : O::get('Req');
  }

  public function getAbsBase() {
    if (($subdomain = $this->getSubdomain()) !== false)
      return '//'.$subdomain.'.'.SITE_DOMAIN;
    else
      return $this->getAbsSiteBase();
  }
  
  public function getAbsSiteBase() {
    return '//'.SITE_DOMAIN.O::get('Req')->getBase();
  }

  protected $subdomain;
  
  public function getSubdomain() {
    //if (isset(O::get('Req')->subdomain)) return O::get('Req')->subdomain;
    //if (!Config::getVarVar('mysite', 'enable')) return false;
    if (isset($this->subdomain)) return $this->subdomain;
    $domainParts = explode('.', $_SERVER['HTTP_HOST']);
    $baseDomainLevel = Misc::siteDomainLevel();
    $curLevel = count($domainParts);
    if ($curLevel == $baseDomainLevel) return false;
    if ($curLevel > $baseDomainLevel + 1)
      throw new NgnException('Number of domain parts is incorrect ('.$curLevel.'). Must be '.($baseDomainLevel + 1));
    $this->subdomain = $domainParts[0];
    return $this->subdomain;
  }
  
  static public function url($subdomain) {
    return '//'.$subdomain.'.'.SITE_DOMAIN;
  }

}