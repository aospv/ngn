.pbSubMenu .bcont li {
list-style: none;
}
<? if ($d['marker1']) { ?>
.pbSubMenu .bcont > ul > li {
}
.pbSubMenu .bcont > ul > li {
padding-left: <?= $d['marker1']['w']+7 ?>px;
background: url(<?= $d['marker1']['url'] ?>) no-repeat 0px 3px;
}
<? } ?>
<? if ($d['marker1Active']) { ?>
.pbSubMenu .bcont > ul > li.active {
background-image: url(<?= $d['marker1Active']['url'] ?>);
}
<? } ?>
<? if ($d['marker2']) { ?>
.pbSubMenu .bcont > ul > li > ul li {
padding-left: <?= $d['marker2']['w']+7 ?>px;
background: url(<?= $d['marker2']['url'] ?>) no-repeat 0px 6px;
}
<? } else { ?>
.pbSubMenu .bcont > ul > li > ul li {
padding-left: 10px;
}
<? } ?>
