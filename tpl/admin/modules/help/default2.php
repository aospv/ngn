<style>
#help img {
display: block;
border: 5px solid #FEFFBF;
margin: 5px 0px 5px 0px;
}
</style>
<div id="help">
<? foreach ($d['pages'] as $name => $v) { ?>
  <a name="<?= $name ?>"></a><h2><?= $v['title'] ?></h2>
  <? foreach ($v['pages'] as $name2 => $v2) { ?>
    <a name="<?= $name.'.'.$name2 ?>"></a><h3><?= $v2['title'] ?></h3>
    <p><?= $v2['text'] ?></p>
  <? } ?>
<? } ?>
</div>