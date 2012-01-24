<?php

class PmsbAuthorItems extends PmsbAbstract {

  public function initBlocks() {
    $this->addUserGroupInfoBlock();
    $this->addUserInfoBlock();
    $this->addUserTagsBlock();
  }
  
  protected function addUserGroupInfoBlock() {
    if (!$this->ctrl->userGroup or $this->ctrl->page->getS('ownerMode') != 'userGroup') return;
    $this->addBlock(array(
      'colN' => 1,
      'type' => 'userGroupInfo',
      'html' => Tt::getTpl('pmsb/userGroup', $this->ctrl->userGroup),
    ));
  }
  
  protected function addUserInfoBlock() {
    if (isset($this->ctrl->d['itemUser'])) $this->u = $this->ctrl->d['itemUser'];
    elseif (isset($this->ctrl->d['itemsUser'])) $this->u = $this->ctrl->d['itemsUser'];
    if (!isset($this->u)) return;
    $this->addBlock(array(
      'colN' => 1,
      'type' => 'userInfo',
      'html' => Tt::getTpl('pmsb/userInfo',
        $this->u
      )
    ));
  }
  
  protected function addUserTagsBlock() {
    if ($this->ctrl->action != 'list') return;
    if (!isset($this->u) or empty($this->ctrl->page['settings']['userTagField'])) return;
    $tagsSelected = Arr::get($this->ctrl->d['tagsSelected'], 'id');
    $ids = db()->selectCol(
      'SELECT id FROM '.DdCore::table($this->ctrl->page['strName']).' WHERE userId=?d', $this->u['id']);
    if ($ids) {
      $d['tags'] = DdTagsItems::getItemsByIds(
        $this->ctrl->page['strName'],
        $this->ctrl->page['settings']['userTagField'],
        $ids
      );
      foreach ($d['tags'] as $k => $v) {
        $d['tags'][$k]['link'] = DdTags::getLink(
          Tt::getPath(1).'/u.'.$this->u['id'],
          $v
        );
        $d['tags'][$k]['selected'] = in_array($v['id'], $tagsSelected);
      }
      $d['field'] = O::get('DdFieldItems', $this->ctrl->page['strName'])->
        getItemByField('name', $this->ctrl->page['settings']['userTagField']);
    }
    $this->addBlock(array(
      'colN' => 3,
      'class' => 'pbSubMenu',
      'type' => 'userItemsTags',
      'html' => Tt::getTpl('pmsb/userTags', $d)
    ));
  }
  
  public function processDynamicBlockModels(array &$blockModels) {
    $blockModels = Arr::dropBySubKey($blockModels, 'colN', 1);
  }

}