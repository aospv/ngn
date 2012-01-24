<? require STM_PATH.'/css/menu/rounded.css'; ?>

/*------------ Static -----------*/
.hMenu > ul {
padding-top: 5px;
padding-bottom: 5px;
}

/*------------ Dynamic -----------*/
.hMenu > ul > li {
font-size: <?= $d['fontSize'] ?>px;
margin-right: <?= $d['marginRight'] ?>px;
}
.hMenu {
<? if (!empty($d['fontFamily'])) { ?>
font-family: <?= $d['fontFamily'] ?>;
<? } ?>
background: url(/i/img/st/<?= $d['menuSize'] ?>/menu-bg.png) no-repeat;
}
.hMenu > ul > li.active > a,
.hMenu > ul > li.active > i {
background-image: url(/i/img/st/<?= $d['menuSize'] ?>/menu-item.png);
}
.hMenu > ul > li > a, .hMenu li > i {
height: <?= $d['itemHeight'] ?>px;
}
.hMenu > ul > li > a span {
margin-top: <?= $d['spanMarginTop'] ?>px;
}
<? if (!empty($d['fontWeight'])) { ?>
.hMenu > ul > li > a {
font-weight: <?= $d['fontWeight'] ?>;
}
<? } ?>
.hMenu > ul > li.active > a,
.hMenu > ul > li.active > i {
background-color: <?= $d['bgColorActive'] ?>;
}
.hMenu > ul > li.active > a {
color: <?= $d['colorActive'] ?>;
}
.hMenu > ul > li > a {
color: <?= $d['color'] ?>;
}
.hMenu > ul > li.over > a,
.hMenu > ul > li.over > i {
background-image: url(/i/img/st/<?= $d['menuSize'] ?>/menu-item.png);
background-color: <?= $d['bgColorOver'] ?>;
}