<?

q('CREATE TABLE IF NOT EXISTS `pages_log` (
  `pageId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `processTime` float NOT NULL,
  `memory` float NOT NULL
) NGN=InnoDB DEFAULT CHARSET=utf8;');