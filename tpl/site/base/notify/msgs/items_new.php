<p><b>Новые записи в разделе «<?= $d['page']['data']['title'] ?>»</b>:</p>
<?php

/* @var $oDdo Ddo */
$oDdo = $d['oDdo'];
//$oDdo->ddddItemsBegin = '';
//  '`<table cellpadding="4" cellspacing="0" class="itemTable">`';
$oDdo->tplPathItem = 'notify/msgs/elements';
//$oDdo->ddddItemsEnd =
//  '`</table><p><a href="`.$pagePath.`/`.$id.`">Перейти к записи</a></p><hr />`';
print $oDdo->els();

Tt::tpl('notify/msgs/itemsUnsubscribe');