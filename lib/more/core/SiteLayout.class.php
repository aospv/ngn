<?php

class SiteLayout {

  static public function topEnabled() {
    return PageControllersCore::exists('userReg');
  }
  
  static public function menu(CtrlPage $ctlr) {
    if (!$ctlr->userGroup) return Html::baseDomainLinks(StmCore::menu());
    $tags = DdTags::get('blog', 'cat');
    $tags->getCond()->addF('userGroupId', $ctlr->userGroup['id']);
    return Html::subDomainLinks(DdTagsHtml::treeUl(
      $tags->getData(),
      '`<a href="/posts/t2.cat.`.$id.`"><i></i><span>`.$title.`</span></a><i></i><div class="clear"></div>`'
    ), $ctlr->userGroup['name']);
  }

}
