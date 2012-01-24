<?php

/**
 * Сохраняет ссылки на записи в канале, обрабатывая по одной странице за шаг
 */
class GrabberPartialJobSaveListPageItems extends GrabberPartialJob {

  protected function initJobs() {
    // Если кол-во ссылок уже было сохранено в предыдущих шагах, не пересчитываем его заного
    if (($this->jobsTotal = Settings::get('gslPagesCount'.$this->channelId))) {
      return; 
    }
    $this->jobsTotal = $this->oG->getPagesCount();
    if ($this->jobsTotal == -1) {
      // Если getPagesCount() вернула false, значит кол-во страниц неопределено
      $this->unknownTotalCount = true;
    }
    $itemsCount = $this->oG->getItemsCount();
    if ($itemsCount == -1) {
      $this->unknownTotalCount = true;
    }
    if ($this->unknownTotalCount) return;
    Settings::set('gslPagesCount'.$this->channelId, $this->jobsTotal);
    Settings::set('gslItemsCount'.$this->channelId, $this->oG->getItemsCount());
  }
  
  protected function makeJob($n) {
    // ............... dummy ..................
  }
  
  public function makeStep($step) {
    $r = $this->stepData($step);
    if (!$this->oG->saveListPageItems($step)) {
      // Если ссылок нет, считаем шаг последним
      // если шаг посчледний нужно сохранить кол-во ссылок
      // а если ссылок нет и это первый шаг
      $r['complete'] = true;
      $this->complete();
      return $r;
    }
    $r['text'] = '(добавлено ссылок на последней странице: '.count($this->oG->getLastSavedItems()).')';
    return $r;
  }
  
  public function complete() {
    $linksCount = $this->oG->getItemsCount();
    $savedLinksCount = count($this->oG->getSavedListPageItems());
    if ($linksCount != $savedLinksCount)
      throw new NgnException('Saved links count and total links count not equal');
    Settings::set('gslFinished'.$this->channelId, true);
  }
  
}
