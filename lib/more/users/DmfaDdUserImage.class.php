<?php

class DmfaDdUserImage extends DmfaImagePreview {

  public function deleteAttaches($fieldName) {
    File::delete($this->getAttachePath().'/'.$this->oDM->authorId.'.jpg');
    File::delete($this->getAttachePath().'/sm_'.$this->oDM->authorId.'.jpg');
    File::delete($this->getAttachePath().'/md_'.$this->oDM->authorId.'.jpg');
    UsersCore::cleanAvatarCache($this->oDM->authorId);
  }
  
  public function getAttacheFolder() {
    return 'user';
  }
  
  public function getAttachePath() {
    return UPLOAD_PATH.'/'.$this->getAttacheFolder();
  }
  
  public function getAttacheFilenameByEl(FieldEFile $el) {
    Misc::checkEmpty($this->oDM->authorId);
    return $this->oDM->authorId;
  }
  
  public function beforeDelete(FieldEFile $el) {
    UsersCore::cleanAvatarCache($this->oDM->authorId);
  }
  
  public function afterUpdate(FieldEFile $el) {
    UsersCore::cleanAvatarCache($this->oDM->authorId);
  }

}