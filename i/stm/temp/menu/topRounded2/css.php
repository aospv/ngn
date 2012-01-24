<?

$rounded = '/c2/roundCorners/border/ffffff/'.$d['borderColor'].'/'.$d['radius'];
$roundedOver = '/c2/roundCorners/noborder/6CA900/'.$d['radius'];
$height = 29;
$height2 = 0;
$marginTop = 2;
?>
.hMenu a {
color: #FFFFFF;
}
.hMenu li li a {
color: #000000;
}
.hMenu li ul {
left: -2px;
background: url(<?= $rounded ?>) left bottom;
padding-bottom: 3px;
margin-left: 2px;
}
.hMenu li li {
border: none;
font-size: 12px;
}
.hMenu li {
font-size: 16px;
}
.hMenu a {
}

/*
.hMenu > ul > li > a:hover {
color: #FFFFFF;
}
*/

.hMenu > ul > li > a,
.hMenu li > i {
padding-top: 0px;
padding-bottom: 0px;
height: <?= $height ?>px; 
}
.hMenu > ul > li > a span {
display: block;
margin-top: <?= $marginTop ?>px;
}


/* для всех 1-го уровня */
.hMenu > ul > li > a {
float: left;
padding-right: 0px;
padding-left: 10px;
}
.hMenu > ul > li > i {
float: left;
display: block;
width: 10px;
}

.hMenu > ul > li.active > a {
color: #FFFFFF;
background: url(<?= $roundedOver ?>) no-repeat;
}
.hMenu > ul > li.active > i {
background: url(<?= $roundedOver ?>) top right no-repeat;
}

.hMenu > ul > li.over > a {
color: #878B82;
background: url(<?= $rounded ?>) no-repeat;
}
.hMenu > ul > li.over > i {
background: url(<?= $rounded ?>) top right no-repeat;
}

.hMenu > ul > li.hasChildren.active.over > a {
background-image: url(<?= $rounded ?>);
}
.hMenu > ul > li.hasChildren.active.over > i {
background-image: url(<?= $rounded ?>);
}

.hMenu > ul > li.hasChildren > a > span {
/*background: url(/<?= STM_WPATH ?>/menu/topRounded/img/arrow-down.gif) center bottom no-repeat;*/
height: <?= $height2 ?>px;
}
.hMenu > ul > li.hasChildren.active > a > span,
.hMenu > ul > li.hasChildren.over > a > span {
/*background-image: url(/<?= STM_WPATH ?>/menu/topRounded/img/arrow-down-over.gif);*/
}
