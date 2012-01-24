<div class="blockHeader">
  <? /*if (!$d['mysite']) { */ ?>
    <a href="<?= $d['listPath'] ?>" class="hbtn small"><span><?= $d['listBtnTitle'] ? $d['listBtnTitle'] : 'Все' ?></span></a>
    <div class="smIcons bordered">
      <? if ($d['isRss']) { ?>
      <a href="<?= $d['path'] ?>?a=rss" title="RSS «<?= $d['pageTitle'] ?>»" class="sm-rss"><i></i></a>
      <? } ?>
      <!-- <a href="#" title="Подписаться на новые записи раздела «<?= $d['pageTitle'] ?>»" id="btnSubscribe" class="sm-unsubscribed"><i></i></a> -->
    </div>
  <? /*}*/ ?>
  <h2><?= $d['title'] ?></h2>
</div>

<div<?= !empty($d['layout']) ? ' class="ddil_'.$d['layout'].'"' : '' ?>>
<?
//$d['oDdo']->setDebug(true);
//if ($d['mysite'])
  //$d['oDdo']->ddddByName['title'] = 
  //'`<h2><a href="http://`.$authorName.`.'.SITE_DOMAIN.'/`.$pagePath.`/`.$id.`">`.$v.`</a></h2>`';
print $d['oDdo']->els();
?>
</div>

