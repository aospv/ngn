<?php

print Slice::html(
  'beforeTree_'.$d['page']['id'],
  'Над списком подразделов раздела "'.$d['page']['title'].'"'
);

print Menu::ul($d['page']['name'], 1);

print Slice::html(
  'afterTree_'.$d['page']['id'],
  'Под списком подразделов раздела "'.$d['page']['title'].'"'
);
