<?php

$page = DbModelCore::get('pages', 'myProfile', 'controller');
$w = isset($page['settings']['smW']) ? $page['settings']['smW'] : 100;
$h = isset($page['settings']['smH']) ? $page['settings']['smH'] : 100;

print "
.avatar img {
width: {$w}px;
height: {$h}px;
}
";