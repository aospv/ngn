<? if ($d) { ?>
<style>
.pbt_lastComments .avatar img {
width: 25px;
height: 25px;
}
.pbt_lastComments .date {
font-size: 10px;
margin: 0px;
}
.pbt_lastComments .item {
padding-bottom: 0px;
}
.pbt_lastComments .item .dgray {
width: 145px;
margin-right: 0px;
}
</style>
<div class="items">
<? foreach ($d as $v) { ?>
  <div class="item">
    <?php /*<p class="gray"><?= Tt::getUserTag($v['userId'], $v['login']) ?>:</p>*/?>
    <?= UsersCore::avatar($v['userId'], $v['login']) ?>
    <? /*<a href="<?= $v['path']?>" class="gray">(<?= $v['pageTitle']?>)</a> */?>
    <a href="<?= $v['link'] ?>" class="smIcons dgray"><?= Misc::cut($v['text'], 50) ?></a>
    <p class="gray date"><i><?= datetimeStr($v['dateCreate_tStamp']) ?></i></p>
    <div class="clear"><!-- --></div>
  </div>
<? } ?>
</div>
<? } ?>
