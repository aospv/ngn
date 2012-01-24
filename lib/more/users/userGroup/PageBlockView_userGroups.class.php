<?php

class PageBlockView_userGroups extends PageBlockViewAbstract {

  public function html() {
    
    $html = '<h2>'.$this->oPBM['settings']['title'].'</h2><ul>';
    foreach (DbModelCore::collection('userGroup', DbCond::get()->setOrder('title')) as $v) {
      $html .= '<li><a href="'.
        SiteRequest::url($v['name']).'/'.
        DbModelCore::get('pages', 'userGroupHome', 'module')->r['path'].
      '">'.$v['title'].'</a></li>';
    }
    $html .= '</ul>';
    return $html;
  }

}
