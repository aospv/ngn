<?php

class PageBlockView_tags extends PageBlockViewAbstract {

  /**
   * @var DdTagsTagsBase
   */
  public $oTags;
  
  protected function init() {
    $this->cssClass = 'pbSubMenu';
    if ($this->oPBM['settings']['hideSubLevels']) $this->cssClass .= ' hideSubLevels';
    $this->page = DbModelCore::get('pages', $this->oPBM['settings']['pageId']);
    $this->oTags = DdTags::get($this->page['strName'], $this->oPBM['settings']['tagField']);
  }

  public function html() {
    if (!isset($this->oCC)) return '';
    if (!empty($this->oCC->d['tagsSelected']))
      $tagsSelected = DdTagsHtml::treeToList($this->oCC->d['tagsSelected']);
    $html = ($this->oPBM['settings']['title'] ? '<h2>'.$this->oPBM['settings']['title'].'</h2>' : '');
    $html .= '<div class="data">'.json_encode(array('groupId' => $this->oTags->getGroup()->id)).'</div>';
    $param = $this->oTags->getGroup()->isTree() ? 
      't2.`.$groupName.`.`.$id' : 't.`.$groupName.`.`.$name';
    $nodes = $this->oTags->getData();
    $dddd = '`<a href="'.$this->page['path'].'/'.$param.'.`"><i></i><span>`.$title.'.
           ($this->oPBM['settings']['showTagCounts'] ? '` <span>(`.$cnt.`)</span>' : '`').
           '</span></a>`';
    if ($this->oPBM['settings']['showOnlyLeafs'] and 0) {
      $html .= DdTagsHtml::treeOnlyNotEmptyLeafs($nodes, $dddd);
    } else {
      $html .= DdTagsHtml::treeUl(
        $nodes, 
        $dddd,
        (DdCore::isItemsController($this->oCC->page['controller']) and
        isset($tagsSelected)) ? Arr::get($tagsSelected, 'id') : array(),
        $this->oPBM['settings']['showNullCountTags']
      );
    }
    $html .= ($this->oPBM['settings']['hideSubLevels'] ? 
      "\n<script>\nnew Ngn.UlMenu($('block_{$this->oPBM['id']}').getElement('ul'));\n</script>\n" :
      '');
    return $html;
  }
  
}