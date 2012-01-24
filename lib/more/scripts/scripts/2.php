<?php

set_time_limit(0);
$o = new Curl();
$c = $o->get('http://bloodymilk.com/site/audio.php?audio_ltr=All');
preg_match("/<b>All<\/b>(.*)<td background=\"images\/index_13\.png\"/msu", $c, $m);
//die2($m[1]);
preg_match_all("/a href='audio.php\?artist=([^']+)'/", $m[1], $m);
print '<table border="1">';
foreach ($m[1] as $artist) {
  $c = $o->get("http://bloodymilk.com/site/audio.php?artist=$artist");
  preg_match('/Country:([^<]+)</', $c, $m);
  $m[1] = trim($m[1]);
  print "<tr><td>".urldecode($artist)."</td><td>{$m[1]}</td></tr>";
  flush();
}
print '</table>';
die2($m);