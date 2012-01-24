<?

if (count($d)) {
  print '<div class="error"><div class="icon"></div>';
  foreach ($d as $k => $v) {
    print $v.'<br />';
  }
  print '</div>';
}

?>