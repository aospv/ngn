<?php

class CtrlCommonInstall extends CtrlCommon {
	
	public $paramActionN = 2;
	
	protected function init() {
	  die2(222222);
		if (defined('DB_INSTALLED')) throw new NgnException('Database already installed');
	}
	
  function setDefaultTpl() {
    $this->d['mainTpl'] = '_installer/main';
  }

  function action_default() {
    $this->d['tpl'] = '_installer/database';
  	$this->d['host'] = 'localhost';
	  $oDBI = new DbInstaller();
    $oDBI->rootHost = DB_HOST;
    $oDBI->rootUser = DB_USER;
    $oDBI->rootPass = DB_PASS;
    $this->d['installed'] = $oDBI->installed(DB_NAME);
    if ($this->d['installed']) {
    	$this->d['user'] = DB_USER;
    	$this->d['pass'] = DB_PASS;
    	$this->d['name'] = DB_NAME;
    }
  }
  
  function action_json_installDb() {
    $oDBI = new DbInstaller();
    if (!$this->oReq->r['host']) {
    	$this->json['error'] = 'Хост не введён';
    	return;
    }
    if (!$this->oReq->r['user']) {
      $this->json['error'] = 'Пользователь не введён';
      return;
    }
    if (!$this->oReq->r['pass']) {
      $this->json['error'] = 'Пароль не введён';
      return;
    }
    if (!$this->oReq->r['name']) {
      $this->json['error'] = 'Имя базы данных не введено';
      return;
    }
    $oDBI->rootHost = $this->oReq->r['host'];
    $oDBI->rootUser = $this->oReq->r['user'];
    $oDBI->rootPass = $this->oReq->r['pass'];
    $oDBI->addSqlFile(NGN_PATH.'/lib/installer/sql/common2.sql');
    if (!$oDBI->import($this->oReq->r['name'])) {
    	$this->json['error'] = $oDBI->error;
    	return;
    }
    SiteConfig::replaceConstant('database', 'DB_INSTALLED', true);
    SiteConfig::updateConstants('database', array(
      'DB_NAME' => $this->oReq->r['name'],
      'DB_USER' => $this->oReq->r['user'],
      'DB_PASS' => $this->oReq->r['pass'],
      'DB_HOST' => $this->oReq->r['host']
    ));
    $this->json['success'] = true;
  }
  
  public function action_asd() {
    die2(222223333344444);
  }

  	
}