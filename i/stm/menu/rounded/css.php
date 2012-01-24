<?

$d['roundBorderWidth'] = (int)$d['roundBorderWidth'];
$d['submenuWidth'] = 500;

if ($d['roundBorderWidth']) {
  $urlBase = '/c2/roundCorners/border'.$d['roundBorderWidth'].'/';

  $itemHeight = $d['menuHeight'];
  
  if ($d['bgColor']) {
    $rounded = '/'.StmCss::url($urlBase.
      $d['bgColor'].'/'.$d['roundBorderColor'].'/'.$d['radius'].'/0/'.$itemHeight);
  }
  
  $d['bgColorActive'] = $d['bgColorActive'] ?: $d['bgColor'];
  if ($d['bgColorActive'] and $d['roundBorderColorActive']) {
    $roundedActive = '/'.StmCss::url($urlBase.
      $d['bgColorActive'].'/'.$d['roundBorderColorActive'].'/'.$d['radius'].'/500/'.$itemHeight);
  }
  $roundedOver = '/'.StmCss::url($urlBase.
    $d['bgColorOver'].'/'.$d['roundBorderColorOver'].'/'.$d['radius'].'/500/'.$itemHeight);
  
  if ($d['levels'] > 1) {
    $roundedOverHasChildren = '/'.StmCss::url($urlBase.
      $d['bgColorOver'].'/'.$d['roundBorderColorOver'].'/'.$d['radius']);
  }
  
  $d['subRounded'] = '/'.StmCss::url($urlBase.
    $d['bgColorOver'].'/'.$d['roundBorderColor'].'/'.$d['radius'].'/');
    
  $d['subRoundedActive'] = '/'.StmCss::url($urlBase.
    $d['bgColorActive'].'/'.$d['roundBorderColor'].'/'.$d['radius'].'/');

  $d['subRoundedOver'] = '/'.StmCss::url($urlBase.
    $d['bgColorOver'].'/'.$d['roundBorderColorOver'].'/'.$d['radius'].'/'.$d['submenuWidth'].'/700');
    
  $d['subOver'] = '/'.StmCss::url($urlBase.
    $d['bgColorOver'].'/'.$d['roundBorderColorOver'].'/0/'.$d['submenuWidth'].'/700');
    
    
} else {
  if ($d['bgColor']) {
    $rounded = '/'.StmCss::url('/c2/roundCorners/noborder/'.$d['bgColor'].'/0/'.
      $d['radius'].'/0/'.$d['menuHeight']);
	}
	
	if ($d['bgColorActive']) {
    $roundedActive = '/'.StmCss::url('/c2/roundCorners/noborder/'.$d['bgColorActive'].'/0/'.
      $d['radius'].'/0/'.$d['menuHeight']);
	}
  $roundedOver = '/'.StmCss::url('/c2/roundCorners/noborder/'.$d['bgColorOver'].'/0/'.
    $d['radius'].'/0/'.$d['menuHeight']);
  
  if ($d['levels'] > 1) {
    $roundedOverHasChildren = 
      '/'.StmCss::url('/c2/roundCorners/noborder/'.$d['bgColorOver'].'/0/'.
      $d['radius']);
  }
    
  $d['subRounded'] = '/'.StmCss::url('/c2/roundCorners/noborder/'.$d['bgColor'].'/0/'.
    $d['radius'].'/1000/700');
  
  $d['subRoundedOver'] = '/'.StmCss::url('/c2/roundCorners/noborder/'.$d['bgColorOver'].'/0/'.
    $d['radius'].'/1000/700');
}

if ($d['bar']) {
  $d['barBg'] ='/'.StmCss::url('/c2/roundCorners/noborder/'.$d['barColor'].'/0/'.
    ($d['radius']+8).'/2000/'.($d['menuHeight']+($d['barOffset']*2)));
}

?>

<? if ($d['bar']) { ?>
.mainmenu {
background: url(<?= $d['barBg'] ?>) no-repeat;
display: inline-block;
}

.mainmenu > ul {
float: left;
margin: <?= $d['barOffset'] ?>px;
}
.mainmenu {
height: <?= $d['menuHeight']+($d['barOffset']*2) ?>px;
}
<? } else { ?>
.mainmenu {
height: <?= $d['menuHeight'] ?>px;
}
<? } ?>
.mainmenu a {
text-decoration: underline;
}
.mainmenu a span {
display: block;
padding: <?= (int)$d['spanPaddingTop'].'px '.(int)$d['spanPaddingRight'].'px '.(int)$d['spanPaddingBottom'].'px '.(int)$d['spanPaddingLeft'].'px' ?>; 
}
.mainmenu > ul > li > a span {
padding-right: <? $r = $d['spanPaddingRight']-$d['radius']; print $r < 0 ? 0 : $r ?>px;
}

.mainmenu li i,
.mainmenu li a {
background: no-repeat;
}

<? if ($rounded) { ?>
.mainmenu li i,
.mainmenu li a {
background-image: url(<?= $rounded ?>);
}
<? } ?>

<? if ($roundedActive) { ?>
.mainmenu > ul > li.active i,
.mainmenu > ul > li.active a {
background-image: url(<?= $roundedActive ?>);
}
<? } ?>

.mainmenu li.over i,
.mainmenu li.over a {
background-image: url(<?= $roundedOver ?>);
}

.mainmenu > ul > li > i,
.mainmenu > ul > li > a {
height: <?= $d['menuHeight'] ?>px;
float: left;
}

<? if ($d['submenuBorderWidth']) { ?>
.mainmenu li li a {
border-top: <?= $d['submenuBorderWidth'] ?>px solid <?= $d['submenuBorderColor'] ?>;
}
/*
.mainmenu > ul > li.hasChildren.over > a, .mainmenu > ul > li.hasChildren.over > i {
border-bottom: <?= $d['submenuBorderWidth'] ?>px solid <?= $d['submenuBorderColor'] ?>;
}
*/
.mainmenu li li.first a {
border: none;
}
<? } ?>

.mainmenu > ul > li > i {
width: <?= $d['radius'] ?>px;
}

<? if (isset($roundedOverHasChildren)) { ?>
.mainmenu > ul > li.over.hasChildren > i,
.mainmenu > ul > li.over.hasChildren > a {
background-image: url(<?= $roundedOverHasChildren ?>);
}
<? } ?>

.mainmenu li > i {
background-position: top right;
}

.mainmenu li li i,
.mainmenu li li a { 
background: none !important;
}

.mainmenu li li {
border: none;
margin: 0;
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
border: 1px solid #CCCCCC;
background: #FFFFFF;
}
.submenu > ul > li > a span {
padding: 2px 6px 2px 6px;
}
.submenu li.selected a {
color: #555555;
}
.submenu > ul > li > a {
border-radius: 5px;
-moz-border-radius: 5px;
-khtml-border-radius: 5px;
-webkit-border-radius: 5px;
}


.submenuBgP {
background-image: url(<?= $d['subRoundedOver'] ?>);
}

.openRight .submenuBgP.tl {
background-position: left top;
background-image: url(<?= $d['subOver'] ?>);
}
.openLeft .submenuBgP.tr {
background-position: right top;
background-image: url(<?= $d['subOver'] ?>);
}

.openRight.equalWidth .submenuBgP.tr {
background-position: right top;
background-image: url(<?= $d['subOver'] ?>);
}

/*
.openLeft .submenuBgP.tr {
background-position: center center;
}
*/
.submenuBgP.l {
background-position: bottom left;
}
.submenuBgP.r {
background-position: bottom right;
}
.submenuBgP.tl {
background-position: top left;
}
.submenuBgP.tr {
background-position: top right;
}
.submenuBgP.bl {
background-position: bottom left;
}
.submenuBgP.br {
background-position: bottom right;
}
