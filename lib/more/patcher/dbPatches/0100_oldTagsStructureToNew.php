<?

/**
 * Меняет структуру таблиц тэгов на новый формат с ТэгГруппами 
 */

db()->delete(array('new_tags'));

q('CREATE TABLE IF NOT EXISTS `new_tags` (
  `id` int(11) NOT NULL auto_increment,
  `parentId` int(11) NOT NULL,
  `oid` int(11) NOT NULL,
  `groupName` varchar(50) character set utf8 NOT NULL,
  `strName` varchar(50) character set utf8 NOT NULL,
  `title` varchar(255) character set utf8 NOT NULL,
  `cnt` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8');

q("
INSERT INTO new_tags
SELECT
  tags.tagId AS id,
  tags.parentId,
  tags.oid,
  tags.type AS groupName,
  pages.strName,
  tags.title,
  0 AS cnt
FROM tags
LEFT JOIN pages ON pages.id=tags.id
WHERE pages.strName!=''");

db()->delete(array('new_tags_groups'));

q('CREATE TABLE IF NOT EXISTS `new_tags_groups` (
  `id` int(11) NOT NULL auto_increment,
  `strName` varchar(50) character set utf8 NOT NULL,
  `name` varchar(50) character set utf8 NOT NULL,
  `itemsDirected` tinyint(1) NOT NULL,
  `unicalTagsName` tinyint(1) NOT NULL,
  `tree` tinyint(1) NOT NULL,
  PRIMARY KEY  (`strName`,`name`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

foreach (q('SELECT * FROM new_tags GROUP BY groupName') as $v) {
  $a = array(
    'strName' => $v['strName'],
    'name' => $v['groupName'],
    'itemsDirected' => 1,
    'unicalTagsName' => 1,
    'tree' => 0,
  );
  prr($a);
  db()->query('INSERT INTO new_tags_groups SET ?a', $a);
}

db()->delete(array('new_tags_items'));

q('CREATE TABLE IF NOT EXISTS `new_tags_items` (
  `groupName` varchar(50) character set utf8 NOT NULL,
  `strName` varchar(50) character set utf8 NOT NULL,
  `tagId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8');

q('
INSERT INTO new_tags_items
SELECT
  tags_items.type AS groupName,
  pages.strName,
  tags.tagId,
  tags_items.itemId
FROM tags_items
LEFT JOIN pages ON pages.id=tags_items.id
, tags
WHERE
  tags_items.title=tags.title AND
  tags_items.type=tags.type AND
  tags_items.id=tags.id
');

db()->replace('new_tags', 'tags');
db()->replace('new_tags_items', 'tags_items');
db()->replace('new_tags_groups', 'tags_groups');
