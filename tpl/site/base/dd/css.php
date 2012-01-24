<style>
<? if (isset($d['page']['settings']['mozaicW'])) { ?>
.str_<?= $d['page']['strName'] ?>.ddItems .thumb img {
width: <?= $d['page']['settings']['mozaicW'] ?>px;
height: <?= $d['page']['settings']['mozaicH'] ?>px;
}
.ddil_tile .item {
width: <?= $d['settings']['mozaicW']+4 ?>px;
}
<? } elseif ($d['page']['settings']['smW']) { ?>
.str_<?= $d['page']['strName'] ?>.ddItems .thumb img {
max-width: <?= $d['page']['settings']['smW'] ?>px;
max-height: <?= $d['page']['settings']['smH'] ?>px;
}
.str_<?= $d['page']['strName'] ?>.ddItems .thumb.halfSize img {
max-width: <?= round($d['page']['settings']['smW']/2) ?>px;
max-height: <?= round($d['page']['settings']['smH']/2) ?>px;
}

.ddil_tile .item {
width: <?= ($d['page']['settings']['smW']+4) ?>px;
}
<? } ?>

.hgrpt_floatBlock {
width: <?= ($d['page']['settings']['smW']-55) ?>px;
}
</style>
