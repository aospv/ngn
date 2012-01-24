<?php

abstract class CtrlPageNotify extends CtrlPage {
  
  /**
   * @var   Notify_SubscribeTypes
   */
  public $oST;
  
  protected $userId;
  
  function init() {
    $this->userId = Auth::get('id');
    if (!$this->userId)
      throw new NgnException('User must be authorized');
  }
  
  function action_delete_comments_newMsgs() {
    if (!isset($this->oReq->r['pageId'])) throw new NgnException('pageId not defined');
    if (!isset($this->oReq->r['itemId'])) throw new NgnException('itemId not defined');
    Notify_SubscribeItems::delete(
      $this->userId, 'comments_newMsgs', $this->oReq->r['pageId'], $this->oReq->r['itemId']);
    $this->redirect();
  }
  
  function action_ajax_delete_comments_newMsgs() {
    if (!isset($this->oReq->r['pageId'])) throw new NgnException('pageId not defined');
    if (!isset($this->oReq->r['itemId'])) throw new NgnException('itemId not defined');
    Notify_SubscribeItems::delete(
      $this->userId, 'comments_newMsgs', $this->oReq->r['pageId'], $this->oReq->r['itemId']);
  }
  
  function action_ajax_subscribeOwnItemsAndSetAllMethodsOn() {
    if (!Auth::get('id')) throw new NgnException('Authorize!');
    if (!UsersSettings::get(Auth::get('id'), 'sendMethods')) {
      UsersSettings::set(
        Auth::get('id'),
        'sendMethods',
        array_values(O::get('Notify_Sender')->getSendMethods())
      );
    }
    Notify_SubscribeTypes::add(
      Auth::get('id'),
      'items_ownChange'
    );
    print '<p>Подписано успешно</p>';
  }
  
  function action_delete_items_new() {
    if (!isset($this->oReq->r['pageId'])) throw new NgnException('pageId not defined');
    Notify_SubscribePages::delete(
      $this->userId, 'items_new', $this->oReq->r['pageId']);
  }
  
  function action_ajax_delete_items_new() {
    if (!isset($this->oReq->r['pageId'])) throw new NgnException('pageId not defined');
    Notify_SubscribePages::delete(
      $this->userId, 'items_new', $this->oReq->r['pageId']);
  }
  
  function action_update() {
    Notify_SubscribeTypes::update(
      Auth::get('id'),
      !empty($this->oReq->r['types']) ? array_keys($this->oReq->r['types']) : array());
    $this->redirect();
  }
  
  function action_sendMethods() {
    $oF = new Notify_SendMethodForm();
    if ($oF->isSubmittedAndValid()) $this->redirect();
    $this->d['form'] = $oF->html();
    $this->d['tpl'] = 'common/form';
    $this->setPageTitle('Методы отправки уведомлений');
    $this->setSubmenu();
  }
  
  function action_default() {
    $this->setPageTitle('Настройка уведомлений');
    $this->d['userTypes'] = Notify_SubscribeTypes::getUserTypes(Auth::get('id'));
    $this->d['types'] = Notify_SubscribeTypes::getTypes();
    $this->d['subItems_comments_newMsgs'] = Notify_SubscribeItems::getSubscribedItems(
      $this->userId, 'comments_newMsgs');
    $this->d['subItems_items_new'] = Notify_SubscribePages::getSubscribedItems(
      $this->userId, 'items_new');
    $this->d['tpl'] = 'notify/default';
    $this->setSubmenu();
  }
  
  function setSubmenu() {
    $this->d['submenu'] = getLinks(array(
      array(
        'title' => 'Настройка уведомлений',
        'link' => Tt::getPath(1),
        'name' => 'default'
      ),
      array(
        'title' => 'Методы отправки',
        'link' => Tt::getPath(1).'/sendMethods',
        'name' => 'sendMethods'
      ),
    ), $this->action);
  }
  
}