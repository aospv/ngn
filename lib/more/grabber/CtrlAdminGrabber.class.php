<?php

/**
 * 
    Settings::get('gslItems'.$channelId);      // сохраненные записи из канала
    Settings::get('gslItemsCount'.$channelId); // общее число записей
    Settings::get('gslPagesCount'.$channelId); // общее число страниц
    Settings::get('gslFinished'.$channelId);   // сохранение ссылок завершено
 * 
 */
class CtrlAdminGrabber extends CtrlAdminPagesBase {

  static $properties = array(
    'title' => 'Граббер записей',
    'onMenu' => false
  );

  protected function init() {
    parent::init();
    if (!$this->pageId)
      throw new NgnException('Using without pageId not allowed');
  }

  protected $table = 'grabberChannel';

  protected function initList() {
    $periods = CronPeriod::getPeriods();
    // Частота обновлений (в секундах)
    $this->d['frequency'] = $periods[Config::getVarVar('grabber', 'period')]['min'] * 60;
    $items = DbModelCore::collection('grabberChannel', DbCond::get()->addF('pageId', $this->pageId));
    foreach ($items as $k => $v) {
      $v['type'] = array(
        'name' => $v['type'],
        'title' => Grabber::getTitle($v['type'])
      );
      $oGS = Grabber::getSource($v['id']);
      $v['unknownTotalCount'] = $oGS->unknownTotalCount;
      $v['saveLinksFinished'] = (bool)Settings::get('gslFinished'.$v['id']);
      if ($v['saveLinksFinished'])
        $v['lastStep'] = (int)$this->getPjLastStep(
          new GrabberPartialJobImporter($v['id']))+1;
      else
        $v['lastStep'] = (int)$this->getPjLastStep(
          new GrabberPartialJobSaveListPageItems($v['id']))+1;
      $v['itemsCount'] = count(Settings::get('gslItems'.$v['id']));
      $v['linksCount'] = (int)Settings::get('gslItemsCount'.$v['id']);
      $v['importedCount'] = (int)Settings::get('giCount'.$v['id']);
      $this->d['items'][$k] = $v;
    }
  }

  public function action_default() {
    $this->initList();
    $this->setPageTitle('Список каналов');
  }

  protected function getStep2Fields($type) {
    foreach (Grabber::getStruct($type)->getFields() as $name => $v) {
      $v['name'] = $name;
      $fields[] = $v;
    }
    return $fields;
  }

  public function action_json_newStep1() {
    $oF = new Form(new Fields(array(array(
      'title' => 'Тип канала', 
      'name' => 'type', 
      'type' => 'select', 
      'options' => Arr::get(Grabber::getTypes(), 'title', 'name'), 
      'required' => true
    ))), array('submitTitle' => 'Продолжить'));
    if ($oF->isSubmittedAndValid()) {
      $this->json['nextFormUrl'] = Tt::getPath(3).'/json_newStep2?'.http_build_query($oF->getData());
      return;
    }
    $this->json['title'] = 'Создание нового канала » Шаг 1. Выбор типа канала';
    return $oF;
  }

  public function action_json_newStep2() {
    $oF = new Form(new Fields($this->getStep2Fields($this->oReq->rq('type'))), array(
      'submitTitle' => 'Создать'
    ));
    foreach (Grabber::getStruct($this->oReq->rq('type'))->getVisibilityConditions() as $cond)
      $oF->addVisibilityCondition($cond[0], $cond[1], $cond[2]);
    if ($oF->isSubmittedAndValid()) {
      $i['type'] = $this->oReq->rq('type');
      $i['data'] = $oF->getData();
      $i['pageId'] = $this->pageId;
      DbModelCore::create('grabberChannel', $i);
      return;
    }
    $this->json['title'] = 'Создание нового канала. Завершение';
    return $oF;
  }

  public function action_json_edit() {
    $curData = DbModelCore::get('grabberChannel', $this->oReq->rq('id'));
    $oF = new Form(new Fields($this->getStep2Fields($curData['type'])));
    $data = $oF->setElementsData($curData['data']);
    foreach (Grabber::getStruct($curData['type'])->getVisibilityConditions() as $cond)
      $oF->addVisibilityCondition($cond[0], $cond[1], $cond[2]);
    if ($oF->isSubmittedAndValid()) {
      DbModelCore::update('grabberChannel', $this->oReq->rq('id'),
        array('data' => $oF->getData()));
      return;
    }
    $this->json['title'] = 'Редактирование свойств канала «'.$curData['data']['title'].'»';
    return $oF;
  }

  public function action_ajax_reload() {
    $this->initList();
    Tt::tpl('admin/modules/grabber/default', $this->d);
  }

  public function action_ajax_test() {
    $oG = Grabber::getSource($this->oReq->rq('id'));
    $n = 0;
    if (($items = $oG->getTestDdItems())) {
      print '<h2>Тестирование прошло успешно</h2><small>';
      foreach ($items as $v) {
        $n++;
        print "<b>Запись #$n</b>".'<h3><a href="'.$v['link'].'" target="_blank">'.
              $v['title'].'</a></h3>'.$v['text'].'<hr />';
      }
      print "</small>";
    } else {
      print '<div class="error">'.$oG->error.'</div>';
    }
    print '</body>';
  }

  public function action_json_importNew() {
    $oGI = new GrabberDdImporter(Grabber::getSource($this->oReq->rq('id')));
    try {
      $this->json['importedCount'] = $oGI->importNew() ? count($oGI->importNew()) : 0;
    } catch (NgnException $e) {
      $this->json['error'] = $e->getMessage();
    }
  }

  /**
   * Partial Job Action
   * Шаг импорта записей
   */
  public function action_json_importAll() {
    $this->actionJsonPJ(new GrabberPartialJobImporter($this->oReq->rq('id')), 'gi');
  }

  /**
   * Partial Job Action
   * Шаг сохранения ссылок на страницы записей
   */
  public function action_json_saveLinks() {
    $this->actionJsonPJ(new GrabberPartialJobSaveListPageItems($this->oReq->rq('id')));
  }

  public function action_saveLinks() {
    new GrabberPartialJobSaveListPageItems($this->oReq->rq('id'));
  }
  
  /**
   * Удаляет полученные для канала ссылки, сбрасывая таким образом все записи канала
   */
  public function action_ajax_deleteLinks() {
    $channelId = $this->oReq->rq('id');
    $this->cleanupPJStep(new GrabberPartialJobSaveListPageItems($channelId));
    Settings::delete('gslItems'.$channelId);
    Settings::delete('gslItemsCount'.$channelId);
    Settings::delete('gslPagesCount'.$channelId);
    Settings::delete('gslFinished'.$channelId);
    $this->ajaxSuccess = true;
  }
  
  /**
   * Удаляет полученные для канала ссылки, сбрасывая таким образом все записи канала
   */
  public function action_ajax_deleteAllImported() {
    $channelId = $this->oReq->rq('id');
    Settings::delete('gslFinished'.$channelId);
    Settings::delete('giCount'.$channelId);
    $this->cleanupPJStep(new GrabberPartialJobImporter($channelId));
  }
  
  public function action_ajax_activate() {
    DbModelCore::update('grabberChannel', $this->oReq->rq('id'), array('active' => 1));
  }
  
  public function action_ajax_deactivate() {
    DbModelCore::update('grabberChannel', $this->oReq->rq('id'), array('active' => 0));
  }
  
  public function action_ajax_delete() {
    DbModelCore::delete('grabberChannel', $this->oReq->rq('id'));
  }

}