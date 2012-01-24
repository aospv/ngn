<?

/**
 * Тип превьюшки, где только одна картинка
 */
define('VIDEO_PREVIEW_TYPE_ONE', 1);

/**
 * Тип превьюшки, где картинка состоит из 4-х кадров, равномерно 
 * распределённый по длине видео
 */
define('VIDEO_PREVIEW_TYPE_FOUR', 2);

/**
 * Временный каталог, в котором будут создаваться папки для превьюшек
 */
define('VIDEO_PREVIEW_TMP_FOLDER', 'videoPreviewTmp/');

/**
 * Кадр какого шага используется в качестве первого
 */
define('VIDEO_PREVIEW_FIRST_STEP', 15);

/**
 * Генератор превьюшек к видео
 */
class VideoPreview_mplayer {
  
  /**
   * JPEG качество конечной превьюхи
   *
   * @var integer
   */
  public $jpegQuality = 80;
  
  /**
   * Кол-во кадров, через которое будут создаваться картинки из видео
   *
   * @var integer
   */
  public $framestep = 10;
  
  /**
   * @var MplayerCommon
   */
  public $mplayer;
  
  public function __construct() {
    $this->mplayer = new MplayerCommon();
  }

  /**
   * Создаёт JPEG превьюшку из видео файла
   *
   * @param   string  Уникальный идентификатор видео-файла
   * @param   string  Путь до видео-файла
   * @param   integer Ширина конечной превьюшки (в пикселах)
   * @param   integer Высота конечной превьюшки (в пикселах)
   * @param   integer Тип превьюшки (VIDEO_PREVIEW_TYPE_ONE или VIDEO_PREVIEW_TYPE_FOUR)
   */
  public function makePreview($videoId, $videoFilePath, $resultFile, $width = 100, $height = 75,
  $type = VIDEO_PREVIEW_TYPE_FOUR) {
      
    if (!$folderPath = $this->createTmpFolder($videoId)) return false;
    if ($type == VIDEO_PREVIEW_TYPE_FOUR) {
      $frameWidth = round($width / 2);
      $frameHeight = round($height / 2); // высота должна быть на пиксел меньше, потому 
                                         // как композиция иначе сосздаётся неверно
    }
    
    /**
     * @todo Если превьюшки уже существуют, то ресемплить видео незачем... хмм хотя....
     */
    if (!isset($this->mplayer))
      throw new NgnException('$this->mplayer not defined');
    
    if (!$previewFiles = $this->getPreviewFiles($folderPath, $type)) {
      $cmd = "{$this->mplayer} $videoFilePath ".
           "-vf eq2=1,scale=$frameWidth:$frameHeight,framestep={$this->framestep} ".
           "-vo jpeg:outdir=$folderPath:quality={$this->jpegQuality}";
      //$cmd = str_replace('\\', '/', $cmd);
      $r = exec($cmd, $out);
      //prr($cmd);
      //die2($out);
    }
    if (!$previewFiles = $this->getPreviewFiles($folderPath, $type)) return false;
    
    for ($i=0; $i<count($previewFiles); $i++) {
      $previewFiles[$i] = UPLOAD_PATH.'/'.VIDEO_PREVIEW_TMP_FOLDER.$videoId.'/'.$previewFiles[$i];
    }
    
    $imageComposer = new ImageComposer();
    $imageComposer->borderWidth = 0;
    $imageComposer->borderHeight = 0;
    $imageComposer->imageMargin = 1;
    $imageComposer->jpegQuality = $this->jpegQuality;
    
    $imageComposer->compose($width, $height, $previewFiles,
      $resultFile, false);
    
    //Dir::remove($folderPath);
  }
  
  /**
   * Создаёт временный каталог, под файлы кадров
   *
   * @param   integer Уникальный идентификатор видео-файла
   * @return  bool
   */
  protected function createTmpFolder($videoId) {
    if (!is_dir(UPLOAD_PATH.'/'.VIDEO_PREVIEW_TMP_FOLDER))
      mkdir(UPLOAD_PATH.'/'.VIDEO_PREVIEW_TMP_FOLDER);
    $folderAbsPath = UPLOAD_PATH.'/'.VIDEO_PREVIEW_TMP_FOLDER.$videoId.'/';
    $folderPath = './'.UPLOAD_DIR.'/'.VIDEO_PREVIEW_TMP_FOLDER.$videoId;
    if (is_dir($folderAbsPath)) return $folderPath;
    Dir::make($folderAbsPath);
    return $folderPath;
  }
  
  protected function getPreviewFiles($dirPath, $type) {
    $d = dir($dirPath);
    if ($type == VIDEO_PREVIEW_TYPE_ONE) {
      // Выборка файлов для варианта с одним кадром
      while ($entry = $d->read()) {
        if (is_file($dirPath."/".$entry)) {
          $n++;
          if ($n == VIDEO_PREVIEW_FIRST_STEP) $files = array($entry);
        }
      }
      if (!$entrys) return false;
    } else {
      // Выборка файлов для варианта с 4-мя кадрами
      while ($entry = $d->read()) {
        if (is_file($dirPath."/".$entry)) {
          $entrys[] = $entry;
        }
      }
      if (!isset($entrys)) return false;
      sort($entrys);
      $filesStep = round((count($entrys) - VIDEO_PREVIEW_FIRST_STEP) / 4);      
      if ($filesStep > 1) {
        $firstI = VIDEO_PREVIEW_FIRST_STEP;
        $i = $firstI;
        while ($i < $firstI + ($filesStep * 4)) {
          $files[] = $entrys[$i];
          $i += $filesStep;
        }
      }
      elseif (count($entrys) >= 4 + VIDEO_PREVIEW_FIRST_STEP) {
        for ($i = VIDEO_PREVIEW_FIRST_STEP; $i < 4 + VIDEO_PREVIEW_FIRST_STEP; $i++) {
          $files[] = $entrys[$i];
        }
      }
      elseif (count($entrys) < 4 + VIDEO_PREVIEW_FIRST_STEP) {
        for ($i = 0; $i < 4; $i++) {
          $files[] = $entrys[$i];
        }
      }
      elseif (count($entrys) == 4) {
        $files = $entrys;
      } else {
        $files[0] = $entrys[0];
      }
    }
    $d->close();
    if (!$files) return false;
    return $files;
  }
  
}
