<? if ($d['footerHeight']) { ?>
/* -- Sticky footer -- */
html, body {
height: 100%;
}
.lwrapper, #layout {
min-height: 100%;
height: auto !important;
height: 100%;
margin: 0 auto -<?= $d['footerHeight'] ?>;
}
.lwrapper, .push {
height: <?= $d['footerHeight'] ?>;
}
#footer {
height: <?= $d['footerHeight'] ?>;
}
<? } ?>
/* ------------------ */

<? if ($d['logo']) { ?>
#logo {
width: <?= $d['logo']['w'] ?>px;
height: <?= $d['logo']['h'] ?>px;
background: url(<?= $d['logo']['url'] ?>) no-repeat;
}
<? } ?>

#menu, #auth {
<? if ($d['logo'] and $d['useLogoWidthAsMenuMargin']) { ?>
margin-left: <?= $d['logo']['w']+(int)$d['marginLeft'] ?>px;
<? } elseif ($d['marginLeft']) { ?>
margin-left: <?= $d['marginLeft'] ?>;
<? } ?>
}



<? if ($d['mainHeaderBg']) { ?>
.mainHeader {
background: url(<?= $d['mainHeaderBg']['url'] ?>) no-repeat;
}
<? } ?>
<? if ($d['homeContainerBg']) { ?>
.home .container {
background: url(<?= $d['homeContainerBg']['url'] ?>) no-repeat center top;
}
<? } ?>
<? if ($d['containerBg']) { ?>
.container {
background: url(<?= $d['containerBg']['url'] ?>) no-repeat center <?= $d['backgroundTopOffset'] ? $d['backgroundTopOffset'] : '0px' ?>;
}
<? } ?>

<? if ($d['homeLayoutBg']) { ?>
#layout.home {
background: url(<?= $d['homeLayoutBg']['url'] ?>) repeat-x top center;
}
<? } ?>

<? if ($d['layoutBg']) { ?>
#layout {
background: url(<?= $d['layoutBg']['url'] ?>) repeat-x top center;
}
<? } ?>

<? if ($d['menuTopMargin']) { ?>
#menu {
margin-top: <?= $d['menuTopMargin'] ?>;
}
<? if ($d['homeTopOffset']) { ?>
.home #menu {
margin-top: <?= (int)$d['menuTopMargin']+$d['homeTopOffset'] ?>px;
}
<? } ?>
<? } ?>

<? if ($d['logoTopMargin']) { ?>
#logo {
margin-top: <?= $d['logoTopMargin'] ?>;
}
<? if ($d['homeTopOffset']) { ?>
.home #logo {
margin-top: <?= (int)$d['logoTopMargin']+$d['homeTopOffset'] ?>px;
}
<? } ?>
<? } ?>

<? if ($d['footerBg']) { ?>
#footer {
background: url(<?= $d['footerBg']['url'] ?>) repeat-x top center;
}
<? } ?>

<? if ($d['thumbRadius']) { ?>
.avatar a, .avatarLarge img, .thumb, .mid-float-box, #mbox-mainbox {
border-radius: <?= $d['thumbRadius'] ?>px;
-moz-border-radius: <?= $d['thumbRadius'] ?>px;
-khtml-border-radius: <?= $d['thumbRadius'] ?>px;
-webkit-border-radius: <?= $d['thumbRadius'] ?>px;
}
.avatar img, .thumb img {
border-radius: <?= $d['thumbRadius']-1 ?>px;
-moz-border-radius: <?= $d['thumbRadius']-1 ?>px;
-khtml-border-radius: <?= $d['thumbRadius']-1 ?>px;
-webkit-border-radius: <?= $d['thumbRadius']-1 ?>px;
}
.mid-float-box {
box-shadow: 0 0 10px black;
-moz-box-shadow: 0 0 10px black;
-webkit-box-shadow: 0 0 10px black;
}
<? } ?>


