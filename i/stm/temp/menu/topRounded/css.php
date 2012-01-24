.hMenu a:focus {
outline: none;
}
.hMenu a {
color: #FFFFFF;
}
.hMenu li ul {
left: 4px;
background: url(/<?= STM_WPATH ?>/menu/topRounded/img/menu2-over.png) left bottom;
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
height: 37px; 
}
.hMenu > ul > li > a span {
display: block;
margin-top: 5px;
}


/* для всех 1-го уровня */
.hMenu > ul > li > a {
float: left;
padding-right: 5px;
padding-left: 15px;
}
.hMenu > ul > li > i {
float: left;
display: block;
width: 10px;
}

.hMenu > ul > li.active > a {
color: #878B82;
background: url(/<?= STM_WPATH ?>/menu/topRounded/img/menu.png) no-repeat;
}
.hMenu > ul > li.active > i {
background: url(/<?= STM_WPATH ?>/menu/topRounded/img/menu.png) top right no-repeat;
}


.hMenu > ul > li.hasChildren.over > a {
color: #878B82;
background: url(/<?= STM_WPATH ?>/menu/topRounded/img/menu-off.png) no-repeat;
}


.hMenu > ul > li.hasChildren.over > i {
background: url(/<?= STM_WPATH ?>/menu/topRounded/img/menu-off.png) top right no-repeat;
}
.hMenu > ul > li.hasChildren.active.over > a {
background-image: url(/<?= STM_WPATH ?>/menu/topRounded/img/menu-over.png);
}
.hMenu > ul > li.hasChildren.active.over > i {
background-image: url(/<?= STM_WPATH ?>/menu/topRounded/img/menu-over.png);
}

.hMenu > ul > li.hasChildren > a > span {
background: url(/<?= STM_WPATH ?>/menu/topRounded/img/arrow-down.gif) center bottom no-repeat;
height: 28px;
}
.hMenu > ul > li.hasChildren.active > a > span,
.hMenu > ul > li.hasChildren.over > a > span {
background-image: url(/<?= STM_WPATH ?>/menu/topRounded/img/arrow-down-over.gif);
}
