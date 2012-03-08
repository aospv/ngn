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
.mainmenu li.active {
background-color: <?= $d['bgColorActive'] ?>;
}