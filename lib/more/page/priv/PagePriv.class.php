<?php

class PagePriv extends NgnArrayAccess {

  /**
   * @var DbModelPages
   */
  protected $page;

  protected $userId;

  public function __construct(DbModel $page, $userId = null) {
    $this->page = $page;
    $this->userId = $userId;
    $this->module();
    $this->first();
    //$this->role();
    $this->last();
  }
  
  public $initPriv = array();
  
  protected function first() {
    if (Misc::isGod() or Misc::isAdmin()) {
      $this->r['edit'] = true;
      $this->r['sub_edit'] = true;
      return;
    }
    $r = db()->select("SELECT userId, type FROM privs WHERE pageId=?d", $this->page['id']);
    if (!$r) return;
    foreach ($r as $v) $this->initPriv[$v['userId']][$v['type']] = 1;
    if ($this->userId) {
      if (isset($this->initPriv[$this->userId])) {
        $this->r = $this->initPriv[$this->userId];
        return;
      } elseif (isset($this->initPriv[REGISTERED_USERS_ID])) {
        $this->r = $this->initPriv[REGISTERED_USERS_ID];
        return;
      }
    }
    if (isset($this->initPriv[ALL_USERS_ID])) $this->r = $this->initPriv[ALL_USERS_ID];
  }
  
  /**
   * Возвращает привелегии для авторизованного пользователя, если пользователь не определен
   * 
   * @return multitype:|boolean
   */
  public function getAuthPriv() {
    if (!$this->userId and isset($this->initPriv[REGISTERED_USERS_ID]))
      return $this->initPriv[REGISTERED_USERS_ID];
    return false;
  }
  
  protected function last() {
    $this->r['view'] = true;
    if (isset($this->r['edit']))
      $this->r['create'] = true; // Если можем редактировать, то можем и создавать
    if (isset($this->r['sub_edit']))
      $this->r['sub_create'] = true; // Если можем редактировать, то можем и создавать
  }
  
  protected function role() {
    if (!Config::getVarVar('role', 'enable', true)) return; 
    // Если включены роли, проверяем доступны ли привелегии для этой роли
    if (($privs = Config::getVarVar('role', 'priv')) === false) return;
    $user = DbModelCore::get('users', $this->userId);
    $privs = Arr::filter_by_value($privs, 'role', $user['role']);
    $privs = Arr::filter_by_value($privs, 'pageId', $this->page['id']);
    foreach ($privs as $v) $this->r[$v['priv']] = 1;
  }
  
  protected function module() {
    if (empty($this->page['module'])) return;
    if (($class = PageModuleCore::getClass($this->page['module'], 'Pmp')) === false) return;
    $this->r = O::get($class, $this->userId)->r;
  }
  
  public function check($priv) {
    if (!in_array($priv, $this->r)) throw new PagePrivException($priv);
  }
  
}
