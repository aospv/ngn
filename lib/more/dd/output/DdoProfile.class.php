<?php

class DdoProfile extends Ddo {
  
  protected function initTpls() {
    parent::initTpls();
    $this->ddddItemsBegin = '';
    $this->ddddItemsEnd = '';
    if (($profilesPath = Tt::getStrControllerPath('profiles', $this->strName, true))) {
      $this->pagePath = $profilesPath;
      $this->ddddByType['select'] = 
        '`<b class="title">`.$title.`:</b> <a href="'.$profilesPath.'/v.`.$name.`.`.$v[`k`].`">`.$v[`v`].`</a>`';
      $this->ddddByType['tagsSelect'] = 
        '`<b class="title">`.$title.`:</b> <a href="'.$profilesPath.'/t2.`.$v[`groupName`].`.`.$v[`name`].`">`.$v[`title`].`</a>`';
    } else {
      $this->ddddByType['select'] = 
        '`<b class="title">`.$title.`:</b> `.$v[`v`]';
      $this->ddddByType['tagsSelect'] = 
        '`<b class="title">`.$title.`:</b> `.$v[`title`]';
      unset($this->tplPathByType['tagsMultiselect']);
      $this->ddddByType['tagsMultiselect'] = 
        '`<b class="title">`.$title.`:</b> `.implode(`, `, (Arr::get($v, `title`)))';
      unset($this->tplPathByType['tags']);
      $this->ddddByType['tags'] = '`<b class="title">`.$title.`:</b> `.implode(`, `, (Arr::get($v, `title`)))';
    }
    
    // Временно ------------ пока не будут реализован поиск по параметрам профиля
      $this->ddddByType['select'] = 
        '`<b class="title">`.$title.`:</b> `.$v[`v`]';
      $this->ddddByType['tagsSelect'] = 
        '`<b class="title">`.$title.`:</b> `.$v[`title`]';
      $this->ddddByType['radio'] = 
        '`<b class="title">`.$title.`:</b> `.$v[`title`]';
    
    
  }
  
}