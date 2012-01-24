<?php

class Comments extends Msgs {
  
  public function __construct($parentId, $id2) {
    parent::__construct('comments', 'parentId', 'id2', $parentId, $id2, 'comments_newMsgs');
  }
  
  public function updateCount() {
    db()->query("
      REPLACE INTO comments_counts SET cnt=?d, parentId=?d, id2=?d",
        db()->selectCell("
        SELECT COUNT(*) FROM comments WHERE
        {$this->id1Field}=?d AND {$this->id2Field}=?d AND active=1",
        $this->id1, $this->id2),
      $this->id1, $this->id2);
  }
  
  static public function _updateCount($parentId, $id2) {
    db()->query("
      REPLACE INTO comments_counts SET cnt=?d, parentId=?d, id2=?d",
        db()->selectCell("
        SELECT COUNT(*) FROM comments WHERE
        parentId=?d AND id2=?d AND active=1",
        $parentId, $id2),
      $parentId, $id2);
 }
  
  /**
   * Переносит комментарии для переносимой записи
   *
   * @param integer ID переносимой записи с комментариями
   * @param integer ID родительского раздела из которого происходит перенос записи
   * @param integer ID родительского раздела в который происходит перенос записи
   */
  static public function move($id2, $parentIdFrom, $parentIdTo) {
    db()->query(
      "UPDATE comments_counts SET parentId=?d WHERE parentId=?d AND id2=?d",
      $parentIdTo, $parentIdFrom, $id2);
    db()->query(
      "UPDATE comments SET parentId=?d WHERE parentId=?d AND id2=?d",
      $parentIdTo, $parentIdFrom, $id2);
  }
  
  static public function getAnswers($userId, $limit) {
    foreach (db()->query("
    SELECT
      comments.*,
      ansComments.text_f AS ansText_f,
      UNIX_TIMESTAMP(comments.dateCreate) AS dateCreate_tStamp,
      UNIX_TIMESTAMP(comments.dateUpdate) AS dateUpdate_tStamp,
      pages.path AS pagePath,
      pages.title AS pageTitle,
      pages.pathData AS pagePathData,
      users.login,
      users2.login AS ansLogin
    FROM comments
    LEFT JOIN pages ON comments.parentId=pages.id
    LEFT JOIN users ON comments.userId=users.id
    LEFT JOIN users AS users2 ON comments.ansUserId=users2.id
    LEFT JOIN comments AS ansComments ON comments.ansId=ansComments.id
    WHERE comments.ansUserId=?d
    ORDER BY comments.dateCreate DESC
    LIMIT $limit
    ", $userId) as $k => $v) {
      $v['link'] = Tt::getPath(0).'/'.$v['pagePath'].'/'.$v['id2'].'#msgs';
      $v['pagePathData'] = unserialize($v['pagePathData']); 
      $items[$k] = $v;
    }
    return $items;
  }

  static protected $userId;
  
  static public function addUserFilter($userId) {
    self::$userId = $userId;
  }
  
  static public function getLast($limit, $pageId = 0) {
    $pageCond = $pageId ? "AND comments.parentId=$pageId" : '';
    $userCond = empty(self::$userId) ? '' : 'AND comments.userId='.self::$userId;
    $limitCond = 'LIMIT '.mysql_escape_string($limit);
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
  pages.mysite,
  pages.title AS pageTitle,
  dd_items.title AS itemTitle
FROM comments_srt
  INNER JOIN comments_active ON
    comments_srt.parentId=comments_active.parentId AND
    comments_srt.id2=comments_active.id2
  INNER JOIN comments ON comments_srt.id=comments.id
  LEFT JOIN users AS users ON comments.userId = users.id
  LEFT JOIN users AS users2 ON comments.ansUserId = users2.id
  LEFT JOIN pages ON comments.parentId = pages.id
  LEFT JOIN dd_items ON comments.parentId = dd_items.pageId AND
                        comments.id2 = dd_items.itemId
WHERE
  comments_srt.active=1 AND
  comments_active.active=1
  $pageCond
  $userCond
ORDER BY comments_srt.id DESC
$limitCond
";
    $r = db()->query($q);
    foreach ($r as &$v) {
      $v['link'] =
      ($v['mysite'] ? 'http://'.$v['userName'].'.'.SITE_DOMAIN.'/' : '').  
      $v['path'].'/'.$v['id2'].'#msgs';
    }
    return $r;
  }
  
  static public function generateActiveRecords() {
    $rowsInInsert = 100;
    $values = array();
    db()->query('TRUNCATE TABLE comments_active');
    $n = 0;
    foreach (db()->ddTables() as $table) {
      $r = mysql_query("SELECT
        pageId AS parentId,
        id AS id2,
        active
        FROM $table");
      while ($v = mysql_fetch_assoc($r)) {
        $values[] = $v;
        $n++;
        if ($n % $rowsInInsert == 0) {
          db()->insertLarge('comments_active', $values);
          $values = array();
        }
      }
    }
    $userDataPageId = db()->selectCell("SELECT id FROM pages WHERE module='userData'");
    $r = mysql_query("SELECT id AS id2 FROM users WHERE active=1");
    while ($v = mysql_fetch_assoc($r)) {
      $v2 = array(
        'parentId' => !empty($v['userDataPageId']) ? $v['userDataPageId'] : $userDataPageId,
        'id2' => $v['id2'],
        'active' => 1
      );
      if (empty($v2['parentId']))
        throw new NgnException("\$v2['parentId'] is empty");
      $n++;
      $values[] = $v2;
      if ($n % $rowsInInsert == 0) {
        db()->insertLarge('comments_active', $values);
        $values = array();
      }
    }
    print memory_get_usage().' Bytes';
  }
  
  static function deleteByItem($pageId, $itemId) {
    db()->query('DELETE FROM comments WHERE parentId=?d AND id2=?d',
      $pageId, $itemId);
    db()->query('DELETE FROM comments_active WHERE parentId=?d AND id2=?d',
      $pageId, $itemId);
    db()->query('DELETE FROM comments_srt WHERE parentId=?d AND id2=?d',
      $pageId, $itemId);
    db()->query('DELETE FROM comments_counts WHERE parentId=?d AND id2=?d',
      $pageId, $itemId);
  }
  
}