<?php

class TinyInit {
  
  protected $themeAdvancedStyles;
  
  protected $clearTags;
  
  public $cssFile;
  
  protected $theme;
  
  protected $themeType;
  
  protected $tags;
  
  public function __construct($themeType) {
    $this->tags = Config::getVar('tiny.'.$themeType.'.allowedTags');
    $this->themeType = $themeType;
    $this->theme = ($themeType == 'site' ? 'advanced' : 'advanced');
    
    // Инициализация пользовательских CSS-классов 
    $_classes = Config::getVar('tiny.'.$themeType.'.classes', true);
    $classes[] = 'Ссылка на скачивание=ifLink';
    $classes[] = 'Превьюшка=iiLink';
    foreach ($_classes as $v) $classes[] = "{$v['title']}={$v['class']}";
    if (is_array($classes)) $this->themeAdvancedStyles = implode(';', $classes);
      
    // Заменяем , на |
    foreach ($this->tags as $k => $v)
      $this->tags[$k] = str_replace(',', '|', $v); 
    $this->clearTags = Misc::clearConfigTags($this->tags); 
    $this->cssFile = SFLM::getCachedUrl('s2/css/common/tiny.css', true);
  }
  
  public function getTheme() {
    return $this->theme;
  }

  /*
   * Возвращает список css-классов через ";"
   */
  public function getThemeAdvancedStyles() {
    return $this->themeAdvancedStyles;
  }
  
  public function getValidElements() {
    return implode(',', $this->tags);
  }
  
  public function getPlugins() {
    if ($this->themeType == 'site') {
      return 'safari,fullscreen,inlinepopups';
    } else {
      return 'safari,inlinepopups,imageuploader,imagesuploader,fileuploader'.
             (in_array('table', $this->clearTags) ? ',table' : '').',fullscreen';
    }
  }

  public function getThemeAdvancedDisable() {
    $disableBtns = array();
    $disableBtns[] = 'help';
    if (!in_array('s', $this->clearTags)) $disableBtns[] = 'strikethrough';
    if (!in_array('blockquote', $this->clearTags)) $disableBtns[] = 'blockquote';
    if (!in_array('sup', $this->clearTags)) $disableBtns[] = 'sup';
    if (!in_array('sub', $this->clearTags)) $disableBtns[] = 'sub';
    if (!in_array('sub', $this->clearTags)) $disableBtns[] = 'sub';
    if (!in_array('u', $this->clearTags)) $disableBtns[] = 'underline';
    if (!in_array('ul', $this->clearTags) or !in_array('li', $this->clearTags)) $disableBtns[] = 'bullist';
    if (!in_array('ol', $this->clearTags) or !in_array('li', $this->clearTags)) $disableBtns[] = 'numlist';
    return implode(',', Arr::append($disableBtns, Config::getVar('tiny.admin.disableBtns')));
  }
  
  public function getThemeAdvancedBlockformats() {
    $tags = array('p');
    foreach (array('h2', 'h3', 'h4') as $_tag)
      if (in_array($_tag, $this->clearTags)) $tags[] = $_tag;
    return implode(',', $tags);
  }
  
  public function getTableButtons() {
    return in_array('table', $this->clearTags) ? 'tablecontrols' : '';
  }
  
}
