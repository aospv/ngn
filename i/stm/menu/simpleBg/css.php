.mainmenu {
padding: 0px 10px 0px 10px;
height: <?= $d['menuHeight'] ?>px;
background: url(<?= $d['backgroundImage']['url'] ?>) no-repeat;
}
.mainmenu a {
height: <?= $d['menuHeight'] ?>px;
}
.mainmenu a span {
display: block;
padding: 12px 20px 0px 20px;
}
.mainmenu li.active a {
background-image: url(<?= $d['linkImageActive']['url'] ?>);
}
.mainmenu li.over a {
background-image: url(<?= $d['linkImageHover']['url'] ?>);
}
