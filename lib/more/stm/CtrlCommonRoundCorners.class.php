<?php

/**
 * Требует установленного ImageMagick'а
 */
class CtrlCommonRoundCorners extends CtrlCommon {
  
  protected function init() {
    $tempPath = TEMP_PATH.'/im/roundCorners';
    Dir::make($tempPath);
    $this->hasOutput = false;
    $this->w = !empty($this->params[6]) ? $this->params[6] : 500;
    $this->h = !empty($this->params[7]) ? $this->params[7] : 500;
    $this->bgColor = '#'.$this->getParam(3);
    $this->borderColor = '#'.$this->getParam(4);
    if (getOS() != 'win') {
      $this->bgColor = "'$this->bgColor'";
      $this->borderColor = "'$this->borderColor'";
    }
    $this->radius = $this->getParam(5);
    $this->file1 = $tempPath.'/'.Misc::randString(10).'.png';
    $this->file2 = $tempPath.'/'.Misc::randString(10).'.png';
    $this->file3 = $tempPath.'/'.Misc::randString(10).'.png';
  }
  
  public function action_noborder() {
    sys("convert -size {$this->w}x{$this->h} xc:none -fill {$this->bgColor} -draw ".
        "\"roundRectangle 0,0 ".($this->w-1).",".($this->h-1)." {$this->radius},{$this->radius}\" {$this->file1}");
    //header('Content-type: image/png');
    print file_get_contents($this->file1);
  }
  
  public function action_border1() {
    $this->border(0, 0, $this->w-3, $this->h-3, $this->radius-1);
  }
  
  public function action_border2() {
    $this->border(1, 1, $this->w-4, $this->h-4, $this->radius-2);
  }
  
  public function action_border3() {
    $this->border(2, 2, $this->w-5, $this->h-5, $this->radius-2);
  }
  
  public function action_border4() {
    $this->border(3, 3, $this->w-6, $this->h-6, $this->radius-3);
  }
  
  public function action_border5() {
    $this->border(4, 4, $this->w-7, $this->h-7, $this->radius-3);
  }
  
  protected function border($x1, $y1, $x2, $y2, $radius) {
    if (!$this->radius) {
      sys("convert -size {$this->w}x{$this->h} xc:none -fill {$this->borderColor} -draw ".
          "\"rectangle 0,0 ".($this->w-1).",".($this->h-1)."\" {$this->file1}");
      sys("convert -size ".($this->w-2)."x".($this->h-2)." xc:none -fill {$this->bgColor} -draw ".
          "\"rectangle $x1,$y1 $x2,$y2\" {$this->file2}");
      sys("convert {$this->file1} {$this->file2} -gravity center -compose Over -composite {$this->file3}");
    } else {
      sys("convert -size {$this->w}x{$this->h} xc:none -fill {$this->borderColor} -draw ".
          "\"roundRectangle 0,0 ".($this->w-1).",".($this->h-1)." {$this->radius},{$this->radius}\" {$this->file1}");
      sys("convert -size ".($this->w-2)."x".($this->h-2)." xc:none -fill {$this->bgColor} -draw ".
          "\"roundRectangle $x1,$y1 $x2,$y2 $radius,$radius\" {$this->file2}");
      sys("convert {$this->file1} {$this->file2} -gravity center -compose Over -composite {$this->file3}");
    }
    File::checkExists($this->file3);
    header('Content-type: image/png');
    print file_get_contents($this->file3);
  }

}
