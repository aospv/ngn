<?php

if (!$d and !($d = $_GET)) throw new NgnException("\$d not defined");
if (!isset($d['colsN'])) throw new NgnException("\$d['colsN'] not defined");

$maxCols = PageBlockCore::$maxXSize;
$minCols = PageBlockCore::$minXSize;
// $colsN - количество колонок
$colsN = isset($d['colsN']) ? (int)$d['colsN'] : 3;
$rowHeight = isset($d['rh']) ? (int)$d['rh'] : 150;
if ($colsN > $maxCols or $colsN < $minCols) throw new NgnException("wrong cols number. Min: $minCols, Max: $maxCols");
$blocksWidth = isset($d['w']) ? $d['w'] : 700;
$marginRight = isset($d['mr']) ? $d['mr'] : 5;
$marginBottom = isset($d['mb']) ? $d['mb'] : 5;

// --------- Output ---------

$width[1] = floor(($blocksWidth - $colsN * $marginRight) / $colsN);
for ($i=$minCols+1; $i<=$maxCols; $i++) {
  $width[$i] = ($width[1] * $i) + $marginRight * ($i-1);
}
$height[1] = $rowHeight;
for ($i=PageBlockCore::$minYSize+1; $i<=PageBlockCore::$maxYSize; $i++) {
  $height[$i] = ($height[1] * $i) + $marginBottom * ($i-1);
}

if (($settings = db()->selectCell('SELECT settings FROM pages WHERE controller=?', myProfile))) {
  $settings = unserialize($settings);
  $textdWidth = $width[1] - ($settings['smW'] + 20);
}

print "
/*

Cols: $colsN
Width: $blocksWidth
Margin right: $marginRight

*/
";

/*
print "
.pageBlocks .col {
width: {$width[1]}px;
}
";

if (isset($textdWidth)) {
print "
.pageBlocks .hgrp_textd {
width: {$textdWidth}px;
}
";
}

foreach (PageBlockCore::getSizePairs() as $v) {
//width: {$width[$v[0]]}px;
  print "
.pageBlocks .size-{$v[0]}-{$v[1]} {
width: 100%;
height: {$height[$v[1]]}px;
}
";
}

?>

.pageBlocks {
width: <?= $blocksWidth ?>px;
}

<?*/
?>


.pageBlocks .col {
width: 33%;
}
.pageBlocks .block {
width: 100%;
}