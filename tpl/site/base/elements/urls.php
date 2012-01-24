<?php

if (empty($d)) return;
$urls = explode(', ', $d);
foreach ($urls as $url) {
  print '<li><a href="http://'.clearUrl($url).'" target="_blank">'.clearUrl($url).'</a></li>';
}
print '</ul>';
