<? if ($d['backgroundImage']) { ?>
<? $d['menuHeight'] = $d['backgroundImage']['h'] ?>
.mainmenu {
padding: 0px 10px 0px 10px;
height: <?= $d['menuHeight'] ?>px;
background: url(<?= $d['backgroundImage']['url'] ?>) no-repeat;
}
<? } ?>
<? if ($d['menuHeight']) { ?>
.mainmenu > ul > li > a {
height: <?= $d['menuHeight'] ?>px;
}
<? } ?>
<? if ($d['linkSeparatorImage']) { ?>
.mainmenu li a {
background: url(<?= $d['linkSeparatorImage']['url'] ?>) top right no-repeat;
}
<? } ?>
<? if ($d['linkImageActive']) { ?>
.mainmenu li.active a {
background: url(<?= $d['linkImageActive']['url'] ?>) top right;
}
<? } ?>
<? if ($d['linkImageHover']) { ?>
.mainmenu li.over a {
background: url(<?= $d['linkImageHover']['url'] ?>) top right;
}
<? } ?>

<? include dirname(dirname(__DIR__)).'/css/menu/bgColors.php' ?>
<? include dirname(dirname(__DIR__)).'/css/submenu/simple.php' ?>