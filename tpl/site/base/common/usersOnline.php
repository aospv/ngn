<?

  $activeTime = !empty($d['activeTime']) ? $d['activeTime'] : 3;

  $users = db()->select("
  SELECT
    users_pages.*,
    users.login
  FROM users_pages
  LEFT JOIN users ON users.id=users_pages.userId
  WHERE
    users_pages.dateCreate > ? AND
    users_pages.userId != 0
  ORDER BY users_pages.dateCreate DESC 
  ", date('Y-m-d H:i:s', time() - (60 * $activeTime)));

?>

<? if ($users) { ?>
  <h2><?= $d['title'] ?> (<?= count($users) ?>)</h2>
  <ul>
    <? foreach ($users as $v) { ?>
    <li>
      <?= Tt::getUserTag($v['userId'], $v['login']) ?><br />
      <small class="gray"><a href="<?= $v['url'] ?>"><?= $v['title'] ?></a></small>
    </li>
    <? } ?>
  </ul>
<? } ?>