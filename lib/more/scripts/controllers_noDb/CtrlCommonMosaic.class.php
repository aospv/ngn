<?php


class CtrlCommonMosaic extends CtrlCommon {

  protected $sessDir, $resultDir, $resultDirPath, $resultFileName, $resultFileAbs, $resultFile, $uploadedImages;

  function init() {
    set_time_limit_q(0);
    $this->hasOutput = false;
    $this->sessDir = UPLOAD_DIR.'/mosaic_temp/' . $_COOKIE['PHPSESSID'] . '/';
    $this->resultDir = UPLOAD_DIR.'/mosaic';
    $this->resultDirPath = UPLOAD_PATH.'/mosaic';
    $this->resultFileName = $_COOKIE['PHPSESSID'].'.jpg';
    $this->resultFileAbs = $this->resultDirPath.'/'.$this->resultFileName;
    $this->resultFile = $this->resultDir.'/'.$this->resultFileName;
    
    Dir::make($this->resultDirPath);
    Dir::make($this->sessDir);
    
    $images = array();
    if (($files = Dir::getFilesR($this->sessDir))) {
      foreach ($files as $file) {
        if (!Image::isImage($file))
          unlink($file);
        else $images[] = $file;
      }
    }
    $this->uploadedImages = $images;
  }

  public function action_uploadArchive() {
    if (isset($_FILES['archive']['tmp_name'])) {
      $zip = new Zip();
      $zip->extract($_FILES['archive']['tmp_name'], $this->sessDir);
      //print 'Распаковано в '.$this->sessDir;
      $this->redirect();
    } else
      throw new NgnException('File not uploaded');
  }

  public function action_createMosaic() {
    if ($this->uploadedImages) {
      $ic = new ImageComposer();
      $ic->mozaicW = $this->oReq->r['bigW'];
      $ic->mozaicH = $this->oReq->r['bigH'];
      $ic->_mosaic($this->oReq->r['w'], $this->oReq->r['h'], $this->uploadedImages, $this->resultFileAbs);
      $this->redirect();
    } else
      throw new NgnException('Images not uploaded');
  }
  
  public function action_deleteAll() {
    Dir::remove($this->sessDir);
  }

  public function action_default() {
    ?>
<style>
.uploader, .dimensions {
border-bottom: 1px solid #CCCCCC;
margin-bottom: 10px;
}
.dimensions .fld {
float: left;
margin-right: 15px;
}
.dimensions .fld input {
width: 50px;
}
.clear {
clear: both;
}
p {
margin: 7px 0px 7px 0px;
}
</style>

<div class="uploader">
<form enctype="multipart/form-data" method="post"
	action="<?=Tt::getPath()?>?a=uploadArchive"><input type="file"
	name="archive" /> <input type="submit" value="Загрузить" /> Допускается
только ZIP формат!</form>
</div>

<?
    if ($this->uploadedImages) {
      ?>
      <div class="dimensions">
<form enctype="multipart/form-data" method="post"
	action="<?=Tt::getPath()?>?a=createMosaic">
<div class="fld">10 x 15 см = 3543 x 2362 пикселов</div>
<div class="fld">Ширина плитки<input type="text" name="w" value="50" /></div>
<div class="fld">Высота плитки<input type="text" name="h" value="50" /></div>
<div class="fld">Ширина полотна<input type="text" name="bigW" value="1000" /></div>
<div class="fld">Высота полотна<input type="text" name="bigH" value="300" /></div>
<div class="clear"></div>
<p>
<input type="submit"
	value="Создать мозаику из <?=count($this->uploadedImages)?>шт. загруженых вами изображений" />
	* Все размеры указаны в пикселях
</form>
</p>
</div>


<?
    }
    
    if (file_exists($this->resultFileAbs)) {
      $sizes = getimagesize($this->resultFileAbs);
      print '<p><b>Результат</b> ('.$sizes[0].'x'.$sizes[1].'):</p>';
      print '<img src="/' . $this->resultFile . '?'.rand(0, 1000).'" />';
    }
    print '<p>Загружено изображений: '.count($this->uploadedImages).'</p>';
    print '<a href="'.Tt::getPath().'?a=deleteAll">Удалить (без предупреждения)</a>';
  }

}
