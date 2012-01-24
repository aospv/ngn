<!-- User CSS -->
<style>
<?php

// $d = user array

list($location, $design, $n) = 
  explode(':', Config::getVarVar('theme', 'theme'));

if (SITE_SET == 'portal') {
  if ($design == 'fullHeader') {
    $bgImage = UPLOAD_PATH.'/mysite/'.$d['id'].'/bg.jpg';
    if (file_exists($bgImage)) {
      $size = getimagesize($bgImage);
      $d['headerBg'] = UPLOAD_DIR.'/mysite/'.$d['id'].'/bg.jpg';
      $d['headerBgSize'] = $size;
      ?>
#layout {
background-image: url(<?= $d['headerBg'] ?>);
background-position: center -<?= $d['headerBgSize'][1]-74 ?>px;
}
.submenu li.selected {
background: url(<?= $d['headerBg'] ?>) bottom;
}
      <?php
    }
  }
}
?>
</style>