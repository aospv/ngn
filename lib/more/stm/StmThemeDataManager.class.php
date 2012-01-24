<?php

class StmThemeDataManager extends StmDataManager {
  
  protected $requiredOptions = array('location');

  protected function defineOptions() {
    $this->options['type'] = 'theme';
    $this->options['subType'] = 'design';
  }
	
  public function getStructure() {
    // Если в опциях менеджера определён дизайн, берём его оттуда,
    // если нет - из объекта данных
    $siteSet = !empty($this->options['siteSet']) ?
      $this->options['siteSet'] :
      $this->getStmData()->data['siteSet'];
    if (empty($siteSet)) throw new EmptyException('$siteSet');
    $design = !empty($this->options['design']) ?
      $this->options['design'] :
      $this->getStmData()->data['design'];
    if (empty($design)) throw new EmptyException('$design');
    // -------------------------------
    return StmCore::getThemeStructure($siteSet, $design);
  }

}
