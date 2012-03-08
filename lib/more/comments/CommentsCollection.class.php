<?php

class CommentsCollection {

  /**
   * @var DbCond
   */
  public $cond;

  public function __construct(DbCond $dbCond) {
    $this->cond = $dbCond;
  }
  
  public function getItems() {
    $q = "
SELECT
  comments.*,
  comments.text_f AS text,
  UNIX_TIMESTAMP(comments.dateCreate) AS dateCreate_tStamp,
  UNIX_TIMESTAMP(comments.dateUpdate) AS dateUpdate_tStamp,
  users.login,
  users.name AS userName,
  users2.login AS ansLogin,
  pages.path,
  pages.title AS pageTitle,
  dd_items.title AS itemTitle
FROM comments_srt
  INNER JOIN comments_active ON
    comments_srt.parentId=comments_active.parentId AND
    comments_srt.id2=comments_active.id2
  INNER JOIN comments ON comments_srt.id=comments.id
  LEFT JOIN users AS users ON comments.userId=users.id
  LEFT JOIN users AS users2 ON comments.ansUserId=users2.id
  LEFT JOIN pages ON comments.parentId=pages.id
  LEFT JOIN dd_items ON comments.parentId=dd_items.pageId AND
                        comments.id2=dd_items.itemId
    ".$this->cond->all();
    $r = db()->query($q);
    foreach ($r as &$v) {
      $v['link'] =
      //($v['mysite'] ? 'http://'.$v['userName'].'.'.SITE_DOMAIN.'/' : '').
      $v['path'].'/'.$v['id2'].'#msgs';
    }
    return $r;
  }

}