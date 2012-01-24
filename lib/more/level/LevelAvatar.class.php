<?php

class LevelAvatar {
  
  public $smW = 0;
  public $smH = 0;
  public $mdW = 0;
  public $mdH = 0;
  protected $smPadding = 2;
  protected $mdPadding = 5;
  
  public function __construct() {
    $settings = unserialize(db()->selectCell(
      'SELECT settings FROM pages WHERE controller=?', 'myProfile'));
    Arr::to_obj_prop(
      $settings,
      $this
    );
    if (empty($this->smW)) throw new NgnException('$this->smW is empty');
    if (empty($this->smH)) throw new NgnException('$this->smH is empty');
    if (empty($this->mdW)) throw new NgnException('$this->mdW is empty');
    if (empty($this->mdH)) throw new NgnException('$this->mdH is empty');
  }
  
  public function generateAll() {
    $files = array();
    foreach (Dir::getFilesR(UPLOAD_PATH.'/users/', '^\d+.*') as $file) {
      $userId = preg_replace('/.*\/(\d+)\.jpg/', '$1', $file);
      $files[$userId] = $file;
    }
    if (!$files) return;
    $levels = db()->selectCol(
    'SELECT userId AS ARRAY_KEY, level FROM level_users WHERE userId IN (?a)',
    array_keys($files));
    
    foreach ($levels as $userId => $level) {
      $this->generateByFile($files[$userId], $level);
    }
  }
  
  public function generateByUser($userId) {
    $file = UPLOAD_PATH.'/users/'.$userId.'.jpg';
    if (!file_exists($file))
      return false;
    if (!($level = db()->selectCell('SELECT level FROM level_users WHERE userId=?d', $userId)))
      return false;
    $this->generateByFile($file, $level);
    return true;
  }
  
  protected function generateByFile($file, $level) {
    $oImage = new Image();
    
    $smFile = Misc::getFilePrefexedPath($file, 'sm_');
    $imSm = $oImage->resize($file, $this->smW, $this->smH);
    $imSmLevel = imagecreatefrompng(NGN_PATH.'/i/img/portal/level/sm'.$level.'.png');
    $smLevelSize = getimagesize(NGN_PATH.'/i/img/portal/level/sm'.$level.'.png');
    imagecopy($imSm, $imSmLevel,
      $this->smW-$smLevelSize[0]-$this->smPadding,
      $this->smH-$smLevelSize[1]-$this->smPadding,
      0, 0, $smLevelSize[0], $smLevelSize[1]
    );
    $oImage->save($imSm, $smFile);
    
    $mdFile = Misc::getFilePrefexedPath($file, 'md_');
    $imMd = $oImage->resample($file, $this->mdW, $this->mdH);
    $imMdLevel = imagecreatefrompng(NGN_PATH.'/i/img/portal/level/md'.$level.'.png');
    $mdLevelSize = getimagesize(NGN_PATH.'/i/img/portal/level/md'.$level.'.png');
    $mdSize = getimagesize($mdFile);
    imagecopy($imMd, $imMdLevel,
      $mdSize[0]-$mdLevelSize[0]-$this->mdPadding,
      $mdSize[1]-$mdLevelSize[1]-$this->mdPadding+1,
      0, 0, $mdLevelSize[0], $mdLevelSize[1]
    );
    $oImage->save($imMd, $mdFile);
  }

}
