<?

// Block Buttons

$btnH = 27;
$rounded = '/'.UrlCache::get('/c2/roundCorners/border/'.
  str_replace('#', '', $d['btn1Color']).'/'.
  str_replace('#', '', $d['borderColor']).'/4/0/'.$btnH, 'png');
$roundedHover = '/'.UrlCache::get('/c2/roundCorners/border/'.
  str_replace('#', '', $d['btn1ColorHover']).'/'.
  str_replace('#', '', $d['borderColor']).'/4/0/'.$btnH, 'png');
?>

.pbt_buttons .bbtn {
margin-bottom: 3px;
display: block;
color: <?= $d['btn1TextColor'] ?>;
text-align: center;
}
.pbt_buttons a.bbtn > span {
height: <?= $btnH ?>px;
display: block;
width: 160px;
float: left;
background: url(<?= $rounded ?>) no-repeat;
}
.pbt_buttons .bbtn i {
float: left;
display: block;
background: url(<?= $rounded ?>) no-repeat right;
width: 10px;
height: <?= $btnH ?>px;
}

.pbt_buttons a.bbtn:hover > span,
.pbt_buttons a.bbtn:hover > i {
background-image: url(<?= $roundedHover ?>);
}

.pbt_buttons .bbtn > span > span {
display: block;
padding-left: 10px;
margin-top: 5px;
}

<?

// Create button

$btnH = 22;
$rounded = '/'.UrlCache::get('/c2/roundCorners/border/'.
  str_replace('#', '', $d['btn1Color']).'/'.
  str_replace('#', '', $d['borderColor']).'/4/0/'.$btnH, 'png');
$roundedHover = '/'.UrlCache::get('/c2/roundCorners/border/'.
  str_replace('#', '', $d['btn1ColorHover']).'/'.
  str_replace('#', '', $d['borderColor']).'/4/0/'.$btnH, 'png');
?>

#pageTitle a.btn {
padding: 0px;
}
#pageTitle a.btn > span {
height: <?= $btnH ?>px;
padding: 0px;
float: left;
}
#pageTitle a.btn > span.a {
width: 10px;
background: url(<?= $rounded ?>) no-repeat;
}
#pageTitle a.btn > span.b {
background: url(<?= $rounded ?>) no-repeat right;
}
#pageTitle a.btn > span span {
padding: 3px 10px 0px 0px;
}