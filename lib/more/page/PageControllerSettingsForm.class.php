<?php

class PageControllerSettingsForm extends DdFormBase {

  /**
   * @var DbModelPages
   */
  protected $page;

  public function __construct(DbModelPages $page) {
    $this->page = $page;
    parent::__construct(
      new PageControllerSettingsFields($page['controller']),
      $page['strName'],
      array(
        'filterEmpties' => true
      )
    );
    if (!empty($this->page['initSettings']))
      $this->setElementsData($this->page['initSettings']);
  }
  
  protected function _update(array $settings) {
    DbModelCore::update('pages', $this->page['id'], array('settings' => $settings));
  }
  
}
