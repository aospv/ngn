<div id="path" class="dgray">
  <? if (count(params())) { ?>
    <?= Tt::enumDddd($d, '`<a href="`.$link.`">`.$title.`</a>`',
      Config::getVarVar('pages', 'pathSeparator')) ?>
  <? } else { ?>
    &nbsp;
  <? } ?>
</div>
