<?php

/**
 * - Преобразует данные из формы в формат для сохранения
 * - Преобразует данные в формат для формы
 */
abstract class DataManagerAbstract extends Options2 {
  
  abstract protected function getItem($id);
  abstract protected function _create();
  abstract protected function _update();
  abstract protected function _delete();
  abstract public function updateField($id, $fieldName, $value);

  /**
   * @var Form
   */
  public $oForm;

  /**
   * HTML сгенерированой формы
   *
   * @var string
   */
  public $formHtml;

  public $imageSizes;
  
  public $videoSizes;
  
  /**
   * Тип ресайза превьюшек (resize/resample)
   *
   * @var string
   */
  public $smResizeType = 'resize';
  
  /**
   * Тип ресайза скринов (resize/resample)
   *
   * @var string
   */
  public $mdResizeType = 'resample';
  
  public $defaultActive = 1;
  
  protected $beforeUpdateData;
  
  static public $defaultImageSizes = array(
    'smW' => 100,
    'smH' => 100,
    'mdW' => 400,
    'mdH' => 300
  );
  
  /**
   * @var Request
   */
  //protected $oReq;
  
  public function __construct(Form $oForm, array $options = array()) {
    if (!is_object($oForm)) throw new NgnException('$oForm is not object');
    $this->oForm = $oForm;
    $this->imageSizes = self::$defaultImageSizes;
    parent::__construct($options);
    $this->initTempId();
    $this->initFancyUpload();
    $this->setAuthorId(isset($this->options['authorId']) ?
      $this->options['authorId'] : Auth::get('id'));
  }
  
  /**
   * Данные записи для сохранения
   *
   * @var array
   */
  public $data;
  
  /**
   * Текущий ID при работе с create, update
   * 
   * @var integer
   */
  public $id = false;
  
  public function getName() {
    return get_class($this);
  }
  
  /**
   * Используется в вызове из контроллера
   * Обрабатывает пользовательские данные, преобразовывая их с помощью класса формы
   * 
   * @param  array  Данные по умолчанию
   */
  public function requestCreate(array $default = array()) {
    $this->oForm->options['submitTitle'] = 'Создать';
    $this->oForm->create = true;
    $this->initTinyInitJs();
    $this->oForm->setElementsData($default);
    if ($this->oForm->isSubmittedAndValid()) {
      return $this->makeCreate();
    }
    return false;
  }
  
  public function create(array $data, $throwFormErrors = true) {
    $this->oForm->fromRequest = false;
    $this->oForm->create = true;
    $this->oForm->setElementsData($data);
    if ($this->oForm->hasErrors) {
      if ($throwFormErrors)
        throw new NgnException($this->oForm->lastError.'. data: '.getPrr($data));
      else return false;
    }
    $r = $this->makeCreate();
    if ($r === false and $throwFormErrors) {
      if (!isset($this->oForm->lastError)) throw new EmptyException('$this->oForm->lastError');
      throw new NgnException($this->oForm->lastError.'. data: '.getPrr($data));
    }
    return $r;
  }
  
  public function createAnyway(array $data) {
    $r = false;
    try {
      $r = $this->create($data);
    } catch (Exception $e) {}
    return $r;
  }
  
  public $defaultData;
  
  /**
   * Производит обработку действия с формы перед созданием, создаёт форму, обрабатывает значения полученные в результате её создания
   * и изменяет значения записи. Последнее делает только в случае если параметр $_data пределен. 
   * 
   * 1) Получает данные, поступившие для апдейта записи либо 
   *    из текщих значений самой записи.
   * 2) Преобразует необходимые значения в вид, необходимый для класса формы
   * 3) Выполняет создание полей формы для каждого из значений записи. Каждое поле
   *    соответственно возвращает преобразованное значение. Преобразования значений 
   *    происходит в соответствующих обработчиках формы (функции формата f_fieldName в
   *    класса формы).
   * 4) Выполняет функцию апдейта записи. 
   *
   * @param   integer ID аписи
   * @param   array   Массив с данными для апдейта
   * @return  bool
   */
  public function requestUpdate($id) {
    if (!is_numeric($id)) throw new NgnException('$id not numeric: '.$id);
    $this->defaultData = $this->getItem($id);
    $this->fieldTypeAction('source2formFormat', $this->defaultData);
    $this->source2formFormat();
    $this->initTinyInitJs($id);
    $this->oForm->setElementsData($this->defaultData);
    if ($this->oForm->isSubmittedAndValid()) {
      return $this->makeUpdate($id);
    }
    return false;
  }
  
  /**
   * Должна работать с массивом $this->defaultData
   */
  protected function source2formFormat() {}
  
  /**
   * Должна работать с массивом $this->data
   */
  protected function form2sourceFormat() {}
  
  public function update($id, array $data, $throwFormErrors = true) {
    $this->oForm->fromRequest = false;
    $this->oForm->setElementsData($data);
    if ($this->oForm->hasErrors) {
      if ($throwFormErrors)
        throw new NgnException($this->oForm->lastError.'. data: '.getPrr($data));
      else return false;
    }
    $r = $this->makeUpdate($id);
    if ($r === false and $throwFormErrors) {
      if (!isset($this->oForm->lastError)) throw new EmptyException('$this->oForm->lastError');
      throw new NgnException($this->oForm->lastError.'. data: '.getPrr($data));
    }
    return $r;
  }
  
  /**
   * @var NgnValidError
   */
  public $validError;
  
  /**
   * Создает запись валидируя входные данные с помощью класса формы
   *
   * @param   array     Пример:
   *                    array(
   *                      'title' => 'The title',
   *                      'file' => array(
   *                        'tmp_name' => '873yq2f.tmp'
   *                      )
   *                    )
   * @return  mixed   Item ID или false, если валидация данных не прошла успешно
   */
  protected function makeCreate() {
    try {
      // Данные необходимо обязательно получать из формы, т.к. обработка их происходит внутри
      // элементов полей. Форма будет возвращать единственно правильный вариант данных
      $this->data = $this->oForm->getData();
      $this->elementTypeAction('beforeCreateUpdate');
      $this->fieldTypeAction('form2sourceFormat', $this->data);
      $this->form2sourceFormat(); 
      $this->addCreateData();
      $this->replaceData();
      $this->beforeCreate();
      $id = $this->_create();
    } catch (NgnValidError $e) {
      $this->validError = $e;
      if (get_class($e) == 'NgnFormError')
        $this->oForm->getElement($e->elementName)->error($e->getMessage());
      else
        $this->oForm->globalError($e->getMessage());
      return false;
    }
    if (empty($id)) throw new NgnException(
      'id is empty. check what '.get_class($this).'::_create returns. create data: '.
      getPrr($this->data));
    $this->id = $id;
    $this->elementTypeAction('afterCreateUpdate', $id);
    $this->elementTypeAction('afterCreate', $id);
    $this->afterCreate();
    $this->afterCreateUpdate();
    return $id;
  }
  
  public function setDataValue($flatName, $value) {
    BracketName::setValue($this->data, $flatName, $value); 
  }
  
  protected function beforeCreate() {}
  protected function afterCreate() {}
  
  public $disableFUdelete = false;
  
  protected function afterCreateUpdate() {
    if (!$this->disableFUdelete and $this->oFUT) $this->oFUT->delete();
  }
  
  protected function makeUpdate($id) {
    $this->id = $id;
    $this->beforeUpdateData = $this->getItemNonFormat($this->id);
    //if (empty($this->beforeUpdateData))
      //throw new NgnException("Item ID={$this->id} does not exists");
    try {
      $this->data = $this->oForm->getData();
      $this->fieldTypeAction('form2sourceFormat', $this->data);
      $this->form2sourceFormat(); 
      $this->replaceData();
      $this->beforeUpdate();
      $this->elementTypeAction('beforeCreateUpdate');
      $this->_update();
    } catch (NgnValidError $e) {
      $this->validError = $e;
      $this->oForm->globalError($e->getMessage());
      return false;
    }
    $this->elementTypeAction('afterCreateUpdate');
    $this->elementTypeAction('afterUpdate');
    $this->afterUpdate();
    $this->afterCreateUpdate();
    return true;
  }
  
  protected function beforeUpdate() {}
  protected function afterUpdate() {}

  protected function getItemNonFormat($id) {
    return $this->getItem($id);
  }

  public function delete($id) {
    $this->id = $id;
    try {
      $this->data = $this->getItem($id);
      if (empty($this->data)) throw new NgnException('No item by id='.$id);
      Dir::remove($this->getAttachePath());
      $this->beforeDelete();
      $this->oForm->setElementsData();
      $this->elementTypeAction('beforeDelete');
    } catch (NgnException $e) {
      $this->_delete($id);
      throw $e;
    }
    $this->_delete($id);
  }
  
  public $authorId = null;
  
  public function setAuthorId($id) {
    $this->authorId = $id;
    return $this;
  }
  
  public function unsetAuthorId() {
    $this->authorId = null;
  }
  
  /**
   * Добавляет или заменяет значения в массиве с данными из формы
   *
   * @param   array   Данные из формы
   */
  protected function replaceData() {
  }
  
  protected function beforeDelete() {
  }
  
  /**
   * @param  string
   * @return Dmfa
   */
  protected function getDmfa($fieldType) {
    if (!O::exists('Dmfa'.ucfirst($fieldType))) return false;
    return O::get('Dmfa'.ucfirst($fieldType), $this);
  }
  
  /**
   * Вызывает статический метод класса FieldE[fieldType] и заменяет с помощью него значение
   * данных
   * 
   * @param  string        $method
   * @param  array         $data
   * @param  integer/null  $id
   */
  protected function fieldTypeAction($method, array &$data) {
    //if (empty($data)) throw new EmptyException($this->getName().': $data');
    foreach (array_keys($data) as $k) {
      if (($fieldType = $this->oForm->oFields->getType($k)) === false) continue;
      if (($o = $this->getDmfa($fieldType)) === false) continue;
      if (!method_exists($o, $method)) continue;
      $data[$k] = $o->$method($data[$k]);
    }
  }
  
  protected function elementTypeAction($method) {
    foreach (array_keys($this->oForm->getElements()) as $k) {
      $el = $this->oForm->getElement($k);
      if (($o = $this->getDmfa($el->type)) === false) continue;
      if (!method_exists($o, $method)) continue;
      $o->$method($el);
    }
  }
  
  /**
   * Типографировать ли HTML для типа 'wisiwig'
   *
   * @var bool
   */
  public $typo = true;
  
  public $tempId;
  
  protected function initTempId() {
    $tempId = session_id();
    if (!$tempId) {
      LogWriter::v('ioooi', 'session ID not defined =(');
      $tempId = isset($_POST['tempId']) ? $_POST['tempId'] : Misc::randString(8);
    }
    $this->oForm->addHiddenField(array(
      'name' => 'tempId',
      'value' => $tempId
    ));
    $this->tempId = $tempId;
    return $this;
  }
  
  /**
   * Используется полями типа "wisiwig"
   */
  public function moveTempFiles(&$html, $itemId, $fieldName) {
    if (!isset($this->tempId))
      throw new NgnException('$this->tempId must be defined. Use DataManagerAbstract::initTempId() after DbItemsManager initialization');
    TinyAttachManager::moveTempFiles(
      $html,
      $this->getTinyAttachTempId($fieldName),
      $this->getTinyAttachItemId($itemId, $fieldName)
    );
  }
  
  public function cleanupImages(&$html, $itemId, $fieldName) {
    TinyAttachManager::cleanupImages(
      $html,
      $this->getTinyAttachItemId($itemId, $fieldName)
    );
  }
  
  public function getTinyAttachTempId($fieldName) {
    Misc::checkEmpty($this->tempId);
    return 'temp-'.$this->tempId.'-'.$fieldName;
  }
  
  public function getTinyAttachItemId($itemId, $fieldName) {
    return 'common-'.$itemId.'-'.$fieldName;
  }
  
  //////////////// STATIC ID ///////////////
  
  public $isStatic;
  
  public $static_id;
  
  public function setStaticId($id) {
    if (empty($id)) throw new NgnException('$id is empty');
    $this->static_id = $id;
    $this->isStatic = true;
    return $this;
  }
  
  public $createData = array();
  
  protected function addCreateData() {
    if ($this->createData) $this->data += $this->createData;
    // Добавляет static_id в данные создаваемой записи
    if ($this->isStatic) {
      if (!$this->static_id) throw new NgnException('$this->static_id not defined');
      $this->data['static_id'] = $this->static_id;
    }
  }
  
  // ------- thumbs ------------

  /**
   * Создаёт превьюшки изображения
   *
   * @param   string    Путь до картинки от корня
   */
  public function makeThumbs($imageRoot) {
    $this->makeSmallThumbs($imageRoot);
    $this->makeMiddleThumbs($imageRoot);
  }

  public function makeSmallThumbs($imageRoot) {
    if (!file_exists($imageRoot))
      throw new NgnException("File '$imageRoot' does not exists");
    if (!isset($this->oImage)) $this->oImage = new Image();
    if ($this->smResizeType == 'resample') {
      $this->oImage->resampleAndSave($imageRoot, 
        Misc::getFilePrefexedPath($imageRoot, 'sm_', 'jpg'),
        $this->imageSizes['smW'], $this->imageSizes['smH']);
    } else {
      $this->oImage->resizeAndSave($imageRoot, 
        Misc::getFilePrefexedPath($imageRoot, 'sm_', 'jpg'), 
        $this->imageSizes['smW'], $this->imageSizes['smH']);
    }
  }

  public function makeMiddleThumbs($imageRoot) {
    File::checkExists($imageRoot);
    if (!isset($this->oImage)) $this->oImage = new Image();
    if ($this->mdResizeType == 'resize') {
      $this->oImage->resizeAndSave($imageRoot, 
        Misc::getFilePrefexedPath($imageRoot, 'md_', 'jpg'), 
        $this->imageSizes['mdW'], $this->imageSizes['mdH']);
    } else {
      $this->oImage->resampleAndSave($imageRoot, 
        Misc::getFilePrefexedPath($imageRoot, 'md_', 'jpg'), 
        $this->imageSizes['mdW'], $this->imageSizes['mdH']);
    }
  }

  public function getAttacheFilenameByEl(FieldEFile $el) {
    if (empty($el->options['postValue'])) throw new Exception('strange =(');
    return $this->getAttacheFilename(
      $el->options['name']
    );
  }

  public function getAttacheFilename($fieldName) {
    return Misc::name2id($fieldName);
  }
  
  public function deleteFile($id, $fieldName) {
    $this->updateField($id, $fieldName, '');
    $this->id = $id;
    if (($dmfa = $this->getDmfa($this->oForm->oFields->fields[$fieldName]['type'])) !== false) {
      if (method_exists($dmfa, 'deleteAttaches'))
        $dmfa->deleteAttaches($fieldName);
    }
  }
  
  /**
   * Только форма созданная из ДатаМенеджера может именть wisiwig-элементы с аттач-кнопками
   */
  protected function initTinyInitJs($itemId = null) {
    if (!$this->oForm->hasAttachebleWisiwig()) return;
    $opt = array(
      'parent' => "$('{$this->oForm->id}')",
      'attachs' => 'true'
    );
    if (!$this->oForm->create) {
      $opt += array('attachIdTpl' => "'".$this->getTinyAttachItemId($itemId, '{fn}')."'");
    } else {
      $opt += array('attachIdTpl' => "'".$this->getTinyAttachTempId('{fn}')."'");
    }
    $opt = Arr::jsObj($opt, false);
    $this->oForm->defaultElements[] = array(
      'type' => 'js',
      'js' => '
new Ngn.TinyInit($merge(
  {settings: new Ngn.TinySettings().getSettings()},
  '.$opt.'
));'
    );
    $this->oForm->tinyInitialized = true;
  }
  
  /**
   * @var FancyUploadTemp
   */
  public $oFUT;
  
  /**
   * Добавляет в объект формы опции для инициализации fancyUpload 
   */
  protected function initFancyUpload() {
    if (getConstant('DISABLE_FANCY_UPLOAD')) return;
    $this->oFUT = O::get('FancyUploadTemp');
    $this->oFUT->extendFormOptions($this->oForm);
  }
  
  /**
   * Должен возвращаеть путь до каталога с изображением относительно UPLOAD_PATH.
   * При вызове этого метода $this->id определен.
   */
  public function getAttacheFolder() {
    throw new NoMethodException('getAttacheFolder');
  }
  
  public function getAttachePath() {
    return UPLOAD_PATH.'/'.$this->getAttacheFolder();
  }
  
  static public function extendImageData(array $data, array $fields) {
    foreach ($fields as $v) {
      if (empty($data[$v['name']])) continue;
      if (FieldCore::hasAncestor($v['type'], 'image')) {
        $data[$v['name']] = '/'.UPLOAD_DIR.'/'.$data[$v['name']];
        if (FieldCore::hasAncestor($v['type'], 'imagePreview')) {
          $data['sm_'.$v['name']] = Misc::getFilePrefexedPath($data[$v['name']], 'sm_', 'jpg');
          $data['md_'.$v['name']] = Misc::getFilePrefexedPath($data[$v['name']], 'md_', 'jpg');
        }
      }
    }
    return $data;
  }
  
}