<?php

if (!$d['o']->strName)
  return '{$this->strName not defined}';
?>
<a href="<?= $d['o']->page['path'].'/list' ?>" class="btn btn2"><span>Все</span></a>
<h2><?= $d['o']->title ?></h2>
<div class="items">
<?php

$d['o']->oDdo->setItems($d['o']->data);
$d['o']->oDdo->ddddByName['title'] = 
  '`<h3><a href="' . $d['o']->page['path'] . '/`.$id.`">`.$v.`</a></h3>`';
$d['o']->oDdo->setFields($d['o']->oDdoFields->getFields());
print $d['o']->oDdo->els();
?>
</div>
