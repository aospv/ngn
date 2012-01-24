<?php

class DmfaImagePreview extends DmfaImage {

  public function afterCreateUpdate(FieldEFile $el) {
    if (($imageRoot = parent::afterCreateUpdate($el)) === false) return false;
    try {
      $this->oDM->makeThumbs($imageRoot);
    } catch (Exception $e) {
      // Если не получилось сделать тумбу
      if ($el->options['required']) {
        // И поле обязательно, удаляем запись
        $this->oDM->delete($this->oDM->id);
      } else {
        // Очищаем поле
        $this->oDM->updateField($this->oDM->id, $el->options['name'], '');
      }
      // и удаляем оригинал
      File::delete($imageRoot);
      throw new NgnValidError($e->getMessage());
    }
    if (($wmConf = Config::getVar('watermark', true)) and $wmConf['enable']) {
      // Делаем вотермарк для превьюшки
      $oIW = new ImageWatermark(WEBROOT_PATH.'/'.$wmConf['path'], 
        $wmConf['rightOffset'], $wmConf['bottomOffset']);
      if ($wmConf['q'])
        $oIW->jpegQuality = $wmConf['q'];
      if (!$oIW->make(Misc::getFilePrefexedPath($imageRoot, 'md_', 'jpg'))) {
        throw new NgnException('watermark error');
      }
    }
  }

}