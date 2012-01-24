.mainmenu li {
float: left;
}
.mainmenu a {
font-size: <?= $d['fontSize'] ?>px;
font-weight: <?= $d['fontWeight'] ?>;
text-decoration: underline;
background: no-repeat 0px 2px;
<? if ($d['linkImage']) { ?>
background-image: url(<?= $d['linkImage']['url'] ?>);
<? } ?>
padding-left: <?= $d['linkImage']['w']+5 ?>px;
}
<? if ($d['linkImageActive']) { ?>
.mainmenu .active a {
background-image: url(<?= $d['linkImageActive']['url'] ?>);
}
<? } ?>
<? if ($d['linkImageHover']) { ?>
.mainmenu a:hover {
background-image: url(<?= $d['linkImageHover']['url'] ?>);
}
<? } ?>

.mainmenu a span {
display: block;
}

