<?php

$urls = explode(', ', $d['v']);
print '<div class="urls"><ul>';
foreach ($urls as $url) {
  print '<li><a href="http://'.clearUrl($url).'" target="_blank">'.clearUrl($url).'</a></li>';
}
print '</ul></div>';
