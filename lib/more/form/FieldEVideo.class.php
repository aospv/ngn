<?php

class FieldEVideo extends FieldEFile {

  protected function validate2() {
    if (empty($this->options['value'])) return;
    try {
      die2(O::get('VideoDecoder')->getInfo($this->options['value']['tmp_name']));
    } catch (NgnException $e) {
      if ($e->getCode() == 311 or $e->getCode() == 322) {
        throw new NgnValidError(
          'Вы пытаетесь загрузить файл не являющийся видео или этот формат просто не поддерживается', 444);
      } else {
        throw new NgnException($e->getMessage());
      }
    }
  }

  // реализовать с очередью
  // public function afterCreateUpdate($itemId, DataManagerAbstract $oDM) {
  //     $data[$k] = '[processing]';
  //     $data['active'] = 0;
  /**
   *

  NgnQueueCore::addJob('DdItemsManager', 'videoConvert', array(
    'pageId' => $this->pageId,
    'itemId' => $itemId,
    'filePath' => $filePath,
    'fieldName' => $k
  ));
    
  static public function videoConvert(array $data) {
    Arr::checkEmpty($data, array('pageId', 'itemId', 'filePath', 'fieldName'));
    if (!($page = NgnOrmCore::getTable('Pages')->find($data['pageId'])))
      throw new NgnException('Page not found');
    $oItems = new DdItems($page->id);
    $newFilePath = UPLOAD_PATH.'/'.File::stripExt($data['filePath']);
    rename(UPLOAD_PATH.'/'.$data['filePath'], $newFilePath);
    if (empty($page->settings['mdW'])) {
      $imageSizes = DdItemsManager::$defaultImageSizes;
    } else {
      $imageSizes = array(
        'mdW' => $page->settings['mdW'],
        'mdH' => $page->settings['mdH']
      );
    }
    $oVideoPreview = new VideoPreview();
    $oVideoPreview->makePreview(
      $newFilePath,
      $imageSizes['mdW'],
      $imageSizes['mdH']
    );
    $oVM = new VideoManager();
    $videoFile = $oVM->make(
      $newFilePath,
      dirname($newFilePath),
      VIDEO_3_W,
      VIDEO_3_H
    );
    $oItems->update($data['itemId'], array(
      $data['fieldName'] => str_replace(UPLOAD_PATH, '', $videoFile),
      $data['fieldName'].'_dur' => $oVideoPreview->getDurationSec($videoFile),
      'active' => 1
    ));
  }
    

   */
  

}
