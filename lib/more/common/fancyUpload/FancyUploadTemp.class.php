<?php

class FancyUploadTemp extends Options2 {

  protected $tempId;
  
  protected $tempFolder;
  
  protected function defineOptions() {
    $this->options['multiple'] = false;
    $this->options['tempId'] = session_id();
  }

  public function __construct(array $options = array()) {
    parent::__construct($options);
    $this->tempId = $this->options['tempId'] ? $this->options['tempId'] : Misc::randString(8);
    $this->tempFolder = Misc::clearLastSlash(TEMP_PATH.'/fancy/'.$this->tempId);
  }
  
  public function getFiles() {
    if (!is_dir($this->tempFolder)) return array();
    $files = array();
    if ($this->options['multiple']) {
      $data = Arr::assoc(
        db()->query('SELECT * FROM upload_temp WHERE tempId=?', $this->tempId),
        'fieldName',
        true
      );
      foreach ($data as $fieldName => $values) {
        foreach ($values as $i => $v) {
          if (!file_exists($this->tempFolder.'/'.$v['fileName'])) continue;
          BracketName::setValue($files, $fieldName."[name][$i]", $v['name']);
          BracketName::setValue($files, $fieldName."[tmp_name][$i]",
            $this->tempFolder.'/'.$v['fileName']);
          BracketName::setValue($files, $fieldName."[size][$i]",
            filesize($this->tempFolder.'/'.$v['fileName']));
        }
      }
    } else {
      $data = Arr::assoc(
        db()->query('SELECT * FROM upload_temp WHERE tempId=?', $this->tempId),
        'fieldName'
      );
      foreach ($data as $fieldName => $v) {
        BracketName::setValue($files, $fieldName, array(
          'name' => $v['name'],
          'tmp_name' => $this->tempFolder.'/'.$v['fileName'],
          'size' => filesize($this->tempFolder.'/'.$v['fileName'])
        ));
      }
    }
    return $files;
  }
  
  public function upload(array $postFiles, $fieldName) {
    Arr::checkEmpty($postFiles, 'Filedata');
    Arr::checkEmpty($postFiles['Filedata'], array('tmp_name', 'name'));
    Dir::make($this->tempFolder);
    $fileName = Misc::randString(10, true);
    copy($postFiles['Filedata']['tmp_name'], $this->tempFolder.'/'.$fileName);
    db()->query('INSERT INTO upload_temp SET ?a', array(
      'tempId' => $this->tempId,
      'fieldName' => $fieldName,
      'fileName' => $fileName,
      'name' => $postFiles['Filedata']['name']
    ));
  }
  
  public function delete() {
    Dir::remove($this->tempFolder);
    db()->query('DELETE FROM upload_temp WHERE tempId=?', $this->tempId);
  }
  
  public function extendFormOptions(Form $oF, $uploadUrl = null) {
    $files = $this->getFiles();
    if (!empty($files)) $oF->options['files'] = $files;
    if (!$uploadUrl) $uploadUrl = '/c2/fancyUpload';
    $uploadUrl = Misc::addParam($uploadUrl, 'tempId', $this->tempId);
    $uploadUrl = Misc::addParam($uploadUrl, 'fn', '{fn}');
    $oF->options['uploadOptions'] = array(
      'url' => $uploadUrl,
      'loadedFiles' => Arr::filter_by_keys2($files, array('name', 'size'))
    );
  }
  
  static public function cleanup() {
    Dir::clear(TEMP_PATH.'/fancy');
    db()->query('TRUNCATE TABLE upload_temp');
  }
  
}
