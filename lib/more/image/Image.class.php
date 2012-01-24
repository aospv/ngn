<?php

define('ERROR_IMAGE_NOT_IMAGE_FILE', 1);
define('ERROR_IMAGE_BAD_IMAGE', 2);
define('ERROR_IMAGE_WRONG_MIME', 3);
define('ERROR_IMAGE_NOTHING_TO_SAVE', 4);


class Image {
  
  /**
   * Массив с кодами ошибок
   *
   * @var   array
   */
  public $errors;
  
  /**
   * Массив с текстами ошибок
   *
   * @var   array
   */
  public $errorsText;
  
  /**
   * Информаяи о изображении
   *
   * @var   array
   */
  public $data;
  
  /**
   * Расширение (тип) картинки: gif, jpg, png, bmp
   *
   * @var   string
   */
  public $exp;
  
  /**
   * Директория для аплоада, находаяся в UPLOADDIR
   *
   * @var   string
   */
  public $uploadSubDir;
  
  /**
   * Сурс изображения, для обработки
   *
   * @var   image
   */
  public $src;
  
  /**
   * Сурс обработанного изображения
   *
   * @var   image
   */
  public $dst;
  
  /**
   * Путь до файла исходного изображения
   *
   * @var   string
   */
  public $path;
  
  /**
   * Качество JPEG'а уменьшенного изображения
   *
   * @var unknown_type
   */
  public $jpegQuality = 90;
  
  
  /**
   * Информация о конечном изображении
   *
   * @var   string
   */
  public $result;
  
  function __construct() {
    $this->errorsText = array(
      ERROR_IMAGE_NOT_IMAGE_FILE => 'Нет файла с изображением',
      ERROR_IMAGE_BAD_IMAGE => 'Файл не являетяс изображением',
      ERROR_IMAGE_WRONG_MIME => 'Данный тип изображения не поддерживается',
      ERROR_IMAGE_NOTHING_TO_SAVE => 'Нечего Сохранять. Нет $this->dst',
    );
  }
  
  function makeUploadedImages($dirPath) {
    $images = getUploadedFiles($dirPath, RENAME_TYPE_TIMESTAMP);
    for ($i=0; $i<count($images); $i++) {
      p($images[$i]);
    }
  }
  
  static public function isImage($imgPath) {
    return GetImageSize($imgPath) ? true : false;
  }
  
  /**
   * Получаем информацию о изображении
   *
   * @param string  Путь до изображения
   */
  function setData($imgPath) {
    if (!$imgPath)
      throw new NgnException("Image path is empty");
    if (!$this->data = GetImageSize($imgPath))
      throw new NgnException("Not image: $imgPath");
    $this->path = $imgPath;
    $this->data['w'] = $this->data[0];
    $this->data['h'] = $this->data[1];
    if ($this->data['mime'] == 'image/jpeg' or $this->data['mime'] == 'image/pjpeg') $this->exp = 'jpg';
    elseif ($this->data['mime'] == 'image/gif') $this->exp = 'gif';
    elseif ($this->data['mime'] == 'image/bmp') $this->exp = 'bmp';
    elseif ($this->data['mime'] == 'image/png' or $this->data['mime'] == 'image/x-png') $this->exp = 'png';
    else return false;
    return true;
  }
  
  /**
   * Создаём изображение
   *
   * @param   string    Путь к файлу изображения
   * @param   string    Тип (расширение) сформированное в резальтате проверки MIME
   */
  function createImage($imgPath) {
    $err_rep = error_reporting(0);
    if ($this->exp == 'jpg') $src = @imageCreateFromJPEG($imgPath);
    elseif ($this->exp == 'gif') $src = @imageCreateFromGIF($imgPath);
    elseif ($this->exp == 'png') $src = @imageCreateFromPNG($imgPath);
    elseif ($this->exp == 'bmp') $src = @imageCreateFromWBMP($imgPath);
    error_reporting($err_rep);
    if ($src) {
      $this->src = $src;
      return true;
    } else {
      throw new NgnException('Wrong Image format');
    }
  }
  
  function rotate($srcPath, $destPath, $degrees = 90) {
    $source = imagecreatefromjpeg($srcPath);
    $this->save(imagerotate($source, $degrees, 0), $destPath);
  }

  /**
   * Интеллектуальное изменение размеров картинки
   * Вписывает в прямоугольник с заданными шириной и высотой
   *
   * @param   integer   Ширина прямоугольника для вписывания
   * @param   integer   Высота прямоугольника для вписывания
   * @param   bool      Увеличивать ли изображение меньшее по размерам, 
   *                    чем заданные для уменьшения размеры (любое ширина или высота)
   * @return  bool
   */
  function _resample($srcPath, $w, $h) {
    Misc::checkEmpty($w); Misc::checkEmpty($h);
  
    if (!$this->createImage($srcPath)) return false;
    $srcW = $this->data['w'];
    $srcH = $this->data['h'];
    
    if ($w > $h) {
      // по ширине      
      $destH = $h;
      $destW = round($srcW * $h / $srcH);
    } else {
      // по высоте
      $destW = $w;
      $destH = round($srcH * $w / $srcW);
    }
    $destW = round($srcW * $h / $srcH);
    $destH = round($srcH * $w / $srcW);
    $destW = ($destW > $w ? $w : $destW);
    $destH = ($destH > $h ? $h : $destH);
    
    Misc::checkEmpty($destW); Misc::checkEmpty($destH);
    
    if (($resultImage = imageCreateTrueColor($destW, $destH)) === false)
      throw new NgnException("Error on create image from '$srcPath' ($destW x $destH)");
    imageCopyResampled($resultImage, $this->src, 0, 0, 0, 0, $destW, $destH, $srcW, $srcH);
    $this->result['type'] = 'resample';
    $this->result['w'] = $destW;
    $this->result['h'] = $destH;    
    return $resultImage;
  }
  
  /**
   * Уменьшает изображение центрируя его по горизонтали и 
   * вертикали и обрезая по заданным размерам
   *
   * @param   string    Путь до изображения
   * @param   integer   Ширина уменьшенного изображения
   * @param   integer   Высота уменьшенного изображения
   * @param   bool      Увеличивать ли изображение меньшее по размерам, 
   *                    чем заданные для уменьшения размеры (любое ширина или высота)
   * @return  bool
   */
  protected function _resize($imgPath, $w, $h) {
    if (!$this->createImage($imgPath)) return false;
    $srcW = $this->data['w'];
    $srcH = $this->data['h'];
    // Подготавливаем изображение с обрезанными размерами
    $resultImage = imageCreateTrueColor($w, $h);
    // Необходимо вычислить размеры изображения, которое мы будем обрезать
    // Вертикальное изображение
    if ($w/$h > $srcW/$srcH) {
      // Пропорции шире, чем были
      $destW = $w;
      $destH = round($srcH * ($destW / $srcW));
      $destX = 0;
      $destY = -round(($destH - $h) / 2);
    } else {
      // Пропорции уже, чем были
      $destH = $h;
      $destW = round($srcW * ($destH / $srcH));
      $destY = 0;
      $destX = -round(($destW - $w) / 2);
    }
    
    imageCopyResampled($resultImage, $this->src, $destX, $destY, 0, 0, $destW, $destH, $srcW, $srcH);
    $this->result['type'] = 'resize';
    $this->result['w'] = $destW;
    $this->result['h'] = $destH;
    return $resultImage;
  }
  
  public function resample($imgPath, $w, $h) {
    if (!$this->setData($imgPath)) return false;
    return $this->_resample($imgPath, $w, $h);
  }
  
  protected $opt;
  
  public function resampleAndSave($imgPath, $destPath, $w, $h, array $options = array()) {
    $this->opt = $options;
    if (!$this->setData($imgPath)) return false;
    if (empty($options['enlargeSmall']) and ($this->data['w'] < $w and $this->data['h'] < $h)) {
      $this->saveInital($destPath);
      return;
    }
    if (!$resultImage = $this->_resample($imgPath, $w, $h))
      return false;
    $this->save($resultImage, $destPath);
  }
  
  public function resize($imgPath, $w, $h) {
    if (!$this->setData($imgPath)) return false;
    return $this->_resize($imgPath, $w, $h);
  }
  
  public function resizeAndSave($imgPath, $destPath, $w, $h, array $options = array()) {
    $this->opt = $options;
    if (!$this->setData($imgPath)) return false;
    if (empty($options['enlargeSmall']) and ($this->data['w'] < $w or $this->data['h'] < $h)) {
      $this->saveInital($destPath);
    }
    if (!$resultImage = $this->_resize($imgPath, $w, $h))
      return false;
    $this->save($resultImage, $destPath);
  }
  
  private function setResultData($path) {
    $this->result['path'] = $path;
    $this->result['mime'] = 'image/jpeg';
    $this->result['exp'] = $this->getExtensionByMime($this->result['mime']);
  }
  
  static function getExtensionByMime($mime) {
    $expressions = array(
      'image/jpeg' => 'jpg',
      'image/pjpeg' => 'jpg',
      'image/png' => 'png',
      'image/x-png' => 'png',
      'image/gif' => 'gif',
      'image/bmp' => 'bmp'
    );
    return isset($expressions[$mime]) ? $expressions[$mime] : false;
  }

  public $destroyAfterSave = true;
  
  public function save($image, $path) {
    $this->setResultData($path);
    imageJpeg($image, $path, $this->jpegQuality);
    if ($this->destroyAfterSave) imagedestroy($image);
    if ($this->src) imagedestroy($this->src);
    return $this->result;
  }
  
  protected function saveInital($destPath) {
    $this->setResultData($destPath);
    copy($this->path, $destPath);
  }
  
  public function outputResult($destPath) {
    header('Content-Type: '.$this->result['mime']);
    print file_get_contents($destPath);
  }
  
}
