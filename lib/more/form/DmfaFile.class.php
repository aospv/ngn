<?php

class DmfaFile extends Dmfa {

  public function deleteAttaches($fieldName) {
    foreach (glob($this->getAttachePath().'/'.$this->getAttacheFilename($fieldName).'*') as $file)
      File::delete($file);
  }

  public function getAttacheFolder() {
    return $this->oDM->getAttacheFolder();
  }
  
  public function getAttachePath() {
    return $this->oDM->getAttachePath();
  }
  
  public function getAttacheFilenameByEl(FieldEFile $el) {
    return $this->oDM->getAttacheFilenameByEl($el);
  }
  
  public function beforeCreateUpdate(FieldEFile $el) {
    if (empty($el->options['postValue'])) return;
    $this->oDM->setDataValue($el->options['name'], '');
  }
  
  protected function getExt(FieldEFile $el) {
    return File::getExtension($el->options['postValue']['tmp_name']);
  }

  public function afterCreateUpdate(FieldEFile $el) {
    // Необходимо запускать постобработку только если есть "value", т.е. если загружен новый файл
    if (empty($el->options['postValue'])) return false;
    $attachFolder = $this->getAttacheFolder();
    $attachPath = $this->getAttachePath();
    Dir::make($attachPath);
    $filename = $this->getAttacheFilenameByEl($el).'.'.$this->getExt($el);
    copy($el->options['postValue']['tmp_name'], $attachPath.'/'.$filename);
    $this->oDM->updateField($this->oDM->id, $el->options['name'], $attachFolder.'/'.$filename);
    return $attachPath.'/'.$filename;
  }

}