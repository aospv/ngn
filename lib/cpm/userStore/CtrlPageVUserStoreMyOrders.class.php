<?php

class CtrlPageVUserStoreMyOrders extends CtrlPage {

  //protected $defaultParamN = 2;

  static public function getVirtualPage() {
    return array(
      'title' => 'Мои заказы'
    );
  }
  
  protected function allowAction($action) {
    return true;
  }
  
  public function action_default() {
    $orders = DbModelCore::collection('userStoreOrder', DbCond::get()->addF('userId', $this->userId));
    foreach ($orders as &$v) {
      $items = DdCore::extendItemsData(
        db()->query('SELECT pageId, itemId FROM userStoreOrderItems WHERE orderId=?', $v['id']));
      $v['items'] = $items;
      $v['data'] = O::get('Form', new UserStoreCustomerFields($this->userId), array(
        'filterEmpties' => true
      ))->setElementsData($v['data'])->getTitledData();
    }
    $this->d['items'] = $orders;
    $this->d['tpl'] = 'userStoreOrder/myOrders';
  }
  
  public function action_ajax_delete() {
    DbModelCore::deleteByCond('userStoreOrder', DbCond::get()->
      addF('userId', $this->userId)->
      addF('id', $this->oReq->rq('id')));
  }

}
