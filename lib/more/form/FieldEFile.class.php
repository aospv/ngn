<?php

/**
 * options[currentFile] - текущий файл (путь относительно UPLOAD_PATH), находящийся в этом поле 
 * options[value] - загруженный в результате поста
 */
class FieldEFile extends FieldEInput {

  public $inputType = 'file';
  
  protected $allowedMimes = null;
  
  public function defineOptions() {
    $this->options = array(
      'help' => 'Максимальный размер файла: '.ini_get('upload_max_filesize'),
      'currentFileTitle' => 'Текущий файл',
      'currentFileClass' => 'file'
    );
  }
  
  protected function init() {
    parent::init();
    if ($this->oForm->fromRequest) {
      $files = isset($this->oForm->options['files']) ?
        $this->oForm->options['files'] :
        $this->oForm->oReq->files;
      $uploadedFileValue = null;
      
      if (!empty($files))
        $uploadedFileValue = BracketName::getValue($files, $this->options['name']);
      if (!empty($uploadedFileValue['error']))
        $uploadedFileValue = null;
      if ($uploadedFileValue !== null) {
        if (empty($uploadedFileValue['tmp_name']))
          throw new EmptyException("{$this->options['name']}: uploadedFileValue['tmp_name']");
        if (!file_exists($uploadedFileValue['tmp_name']))
          throw new NoFileException($uploadedFileValue['tmp_name']);
      }
    } else {
      $uploadedFileValue = !empty($this->options['value']) ? $this->options['value'] : null;
    }
    if ($uploadedFileValue !== null/* and empty($uploadedFileValue['error'])*/) {
      // Если файл загружен
      Arr::checkEmpty($uploadedFileValue, 'tmp_name');
      $mime = File::getMime($uploadedFileValue['tmp_name']);
      Misc::checkEmpty($mime);
      if (
      !empty($this->allowedMimes) and
      !in_array($mime, $this->allowedMimes)
      ) {
        // Если для этого поля определены MIME и MIME загруженного 
        // файла на присутствует в этом списке
        $this->error = "Неправильный формат файла ($mime)";
      } else {
        // 1 состояние
        $this->options['postValue'] = $uploadedFileValue;
        // всё остальное - 2-е
      }
    }
  }

  protected function validate1() {
    if (
    empty($this->options['value']) and 
    empty($this->options['postValue']) and
    !empty($this->options['required'])
    ) {
      $this->error = "Поле «{$this->options['title']}» обязательно для заполнения";
    }
  }
  
  protected function getCurrentValue() {
    return '/'.UPLOAD_DIR.'/'.$this->options['value'];
  }
  
  protected function htmlNav() {
    return (empty($this->options['value']) ? '' :
      '<div class="iconsSet fileNav">'.
        '<a href="'.$this->getCurrentValue().'" class="'.$this->options['currentFileClass'].'" target="_blank"><i></i> '.$this->options['currentFileTitle'].'</a>'.
        ((!empty($this->oForm->options['deleteFileUrl']) and empty($this->options['required'])) ?
          '<a href="'.$this->oForm->options['deleteFileUrl'].'&fieldName='.$this->options['name'].'" class="delete confirm" title="Удалить"><i></i></a>' :
          '').
      '</div>'
    );
  }
  
  public function _html() {
    $params = array(
      'name' => $this->options['name'],
      'value' => $this->options['value'],
      'data-file' => $this->options['value']
    );
    return
      $this->htmlNav().'<input type="file" '.Tt::tagParams($params).' />';
  }
  
  public function value() {
    return empty($this->options['postValue']) ?
      $this->options['value'] : $this->options['postValue'];
  }

}
