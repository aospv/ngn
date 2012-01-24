<?php

class DdoSite extends Ddo {
  
  protected function initTpls() {
    parent::initTpls();
    if (!empty($this->page['settings']['userTagField'])) {
    	// пересмотреть формирование
    	// иногда нужно что бы для определенного условия для полей были другие шаблоны
    	// @TODO !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
      //$this->tplPathByName[$this->page['settings']['userTagField']] = 'dd/userTagsList';
    }
    $this->ddddByName['title'] = 
      '`<h2><a href="`.'.$this->ddddItemLink.'.`">`.$v.`</a></h2>`';
    //$this->tplPathByType['ddTagsTreeConsecutiveSelect'] = 'dd/tagsList';
    $this->ddddByType['date'] = 'dateStrSql($v, `d.m.Y`, `Y-m-d`)';
    $this->ddddByType['datetime'] = 'datetimeStrSql($v)';
    $this->ddddByType['commentsCount'] = '
$v ? (`<div class="smIcons">
<a class="sm-comments`.($v > 2 ? `2` : ``).` shortComments"
title="комментарии (`.$v.`)" 
href="`.$pagePath.`/`.$id.`#msgs"><i></i>
`.$v.`
</a><div class="clear"><!-- --></div>
</div>`) : ``';
  
    $this->ddddByType['clicks'] =
'$v ? `<div class="smIcons"><span class="sm-view" title="Просмотров"><i></i>`.$v.`</span></div>` : ``';
    
  }
  
}
