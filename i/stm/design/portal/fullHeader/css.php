<? if ($d['bgImage']) { ?>
body {
background: url(<?= $d['bgImage']['url'] ?>) top center no-repeat;
}
<? } ?>
<? if ($d['headerBgImage']) { ?>
#layout {
background: url(<?= $d['headerBgImage']['url'] ?>) top center;
}
<? } ?>
#layout {
background-repeat: no-repeat;
}
#logo {
margin-top: <?= $d['logoMarginTop'] ?>px;
margin-left: <?= $d['logoMarginLeft'] ?>px;
width: <?= $d['logoImage']['w'] ?>px;
height: <?= $d['logoImage']['h'] ?>px;
background: url(<?= $d['logoImage']['url'] ?>) no-repeat;
}
#top {
margin-left: <?= $d['logoImage']['w'] ?>px;
width: <?= 950-$d['logoImage']['w'] ?>px;
}
#menu {
margin-top: <?= $d['menuMarginTop'] ?>px;
padding-left: <?= $d['menuMarginLeft'] ?>px;
margin-bottom: <?= $d['menuMarginBottom'] ?>px;
}
#menu, #top {
width: <?= 950-$d['logoImage']['w'] ?>px;
margin-left: <?= $d['logoImage']['w']+15 ?>px;
}
