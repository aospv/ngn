<? if ($d['col2TopPadding']) { ?>
#col1 {
padding-top: <?= $d['col2TopPadding'] ?>px;
}
<? } ?>

<? if ($d['whiteRoundBlocks']) { ?>
  .col .body {
    padding: 0px;
  }
  <? include STM_PATH.'/css/whiteRoundBlocks.css.php'; ?>
<? } ?>