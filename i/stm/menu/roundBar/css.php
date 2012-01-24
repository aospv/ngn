<?

$d['barBg'] ='/'.StmCss::url('/c2/roundCorners/noborder/'.$d['barColor'].'/0/'.
  $d['radius'].'/900/'.$d['menuHeight']);
  
?>
 
.mainmenu {
background: url(<?= $d['barBg'] ?>) no-repeat;
height: <?= $d['menuHeight'] ?>px;
padding-left: <?= $d['radius'] ?>px;
padding-right: <?= $d['radius'] ?>px;
}
.mainmenu li {
height: <?= $d['menuHeight'] ?>px;
}
.mainmenu a span {
display: block;
padding: <?= (int)$d['spanPaddingTop'].'px '.(int)$d['spanPaddingRight'].'px '.(int)$d['spanPaddingBottom'].'px '.(int)$d['spanPaddingLeft'].'px' ?>; 
}
.mainmenu li.active {
background-color: <?= $d['bgColorActive'] ?>;
}