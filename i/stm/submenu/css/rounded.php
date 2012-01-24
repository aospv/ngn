.submenu ul ul {
border-right: 1px solid <?= $d['roundBorderColor'] ?>;
border-left: 1px solid <?= $d['roundBorderColor'] ?>;
left: 0px;
border-top: 1px solid <?= $d['roundBorderColor'] ?>;
}

.submenu li li {
padding: 5px 0px 5px 10px;
border-bottom: 1px solid #878B82;
}
.submenu {
height: 20px;
margin: 5px 0px 0px 0px;
}
.submenu > ul > li > a {
font-weight: bold;
}
.submenu a {
color: #878B82;
}
.submenu li.selected {
}
.submenu li.selected > a {
color: #FFFFFF;
}

.submenu > ul {
padding-left: 1px;
}

/*--------------Фон-----------*/
.submenu > ul > li > a,
.submenu > ul > li > i {
float: left;
height: 26px;
background: url(<?= $d['subRounded'] ?>) no-repeat;
border-bottom: 1px solid <?= $d['roundBorderColor'] ?>;
}

.submenu > ul > li.selected > a,
.submenu > ul > li.selected > i {
background-image: url(<?= $d['subRoundedActive'] ?>);
color: <?= $d['colorActive'] ?>; 
}


.submenu > ul > li > a {
background-position: top left;
}
.submenu > ul > li > i {
display: block;
width: 10px;
background-position: top right;
}

.submenu > ul > li > a span {
padding: 4px 3px 1px 12px;
}

/*-----------------------------*/

/*

.submenu > ul > li > a {
padding-left: 6px;
}

*/

.submenu li li a span {
padding: 0px;
}






