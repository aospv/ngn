<?php

class PbvUserGroups extends PbvAbstract {

  public function _html() {
    $html = '<ul>';
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
