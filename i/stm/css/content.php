<?

/**
 * Стили для элементов находящихся внутри contentBody и mceContentBody
 */

?>

<? if ($d['blockquoteImage']) { ?>
.contentBody blockquote, .mceContentBody blockquote {
background: url(<?= $d['blockquoteImage']['url'] ?>) no-repeat;
padding: 7px 0px 2px;
border-width: 0px;
padding-left: <?= $d['blockquoteImage']['w']+15 ?>px;
}
<? } ?> 

<? if ($d['marker']) { ?>
.contentBody ul li , .mceContentBody ul li {
list-style: none;
background: url(<?= $d['marker']['url'] ?>) no-repeat 0px <?= 10-$d['marker']['h']/2 ?>px;
margin-left: 0px;
padding-left: <?= $d['marker']['w']+10 ?>px;
}
.contentBody ul, .mceContentBody ul {
margin-left: 10px;
}
<? } ?>
