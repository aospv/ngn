<?php

class DispatcherMysite extends DispatcherSite {

  protected function initController() {
    parent::initController();
    if (!Config::getVarVar('mysite', 'enable')) return; 
    if (($subdomain = O::get('Req')->detectSubdomain()) === false) return;
    if (in_array($subdomain, Config::getVar('mysiteReservedSubdomains'))) {
      redirect('http://'.SITE_DOMAIN.Tt::getPath());
      return;
    }
    $homeType = $this->getMysiteHomeType();
    if (!count($this->oReq->params)) {
      // Домашняя страничка
      $page = DbModelCore::get('pages', Config::getVarVar('mysite', 'homePageId'));
    } else {
      // Если есть путь страницы и эта страница страница по умолчанию, 
      // перенаправляем на ссылку без пути
      $page = $this->page;
      if (count($this->oReq->params) == 1 and !isset($_GET['a'])) {
        if ($page['id'] == Config::getVarVar('mysite', 'homePageId'))
          redirect(Tt::getPath(0));
      }
    }
    
    $this->oController = PageControllersCore::getController(
      $page,
      array('subdomain' => $subdomain)
    );
    
    /*
    // ------------- myItems --------------
    if ($homeType == 'items') {
      if (!$page['settings']['mysite']) {
        throw new NgnException('Controller is not allowed for mysite. Change settings');
      }
      $this->oController = O::get(
        'Ctrl'.$page['controller'],
        new Req(),
        array('subdomain' => $subdomain)
      );
      $this->oController->setPage($page);
      
    // ------------- userData -------------
    } elseif ($homeType == 'userData') {
      die2($page);
      
      $this->oController = new CtrlUserData(new Req(), array(
        'subdomain' => $subdomain
      ));
      $this->oController->setPage($page);
      
    // ------------- blocks ---------------
    } else {
      throw new NgnException('blocks type not yet realized');
    }
    //R::set('currentPageId', $page['id']);
    */
    return;
  }  
    
  protected function getMysiteHomeType() {
    //Config::getVarVar('mysite', 'allowHomeRedefineByOwner')
    return Config::getVarVar('mysite', 'homeType');
  }

}