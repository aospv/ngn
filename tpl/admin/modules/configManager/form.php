<form action="<?= Tt::getPath() ?>" method="post">
<input type="hidden" name="action" value="<?= $d['action']?>" />
<ul>
<? foreach ($d['items'] as $name => $v) { ?>
  <li>
    <h2><?= $d['descr']['vars'][$name] ? "<b>{$d['descr']['vars'][$name]}</b> $name" : "$name" ?>:</h2>
    <ul>
      <? foreach ($v as $k => $u) { ?>
        <li>
          <? if (is_array($u)) { ?>
            <input type="text" class="key" name="key<?= "[$name][$k][_key]" ?>" value="<?= $k ?>" /> = 
            <ul>
            <? foreach ($u as $kk => $uu) { ?>
              <li>
                <input type="text" class="key" name="key<?= "[$name][$k][$kk]" ?>" value="<?= $kk ?>" /> = 
                <? if (strstr($uu, "\n")) { ?> 
                  <textarea name="val<?= "[$name][$k][$kk]" ?>" style="width:600px;height:100px;"><?= $uu ?></textarea>
                <? } else { ?>
                  <input type="text" class="val" name="val<?= "[$name][$k][$kk]" ?>" value="<?= $uu ?>" />
                <? } ?>
              </li>
            <? } ?>
              <li>
                <input type="text" class="key" name="key<?= "[$name][$k][]" ?>" value="" /> =
                <input type="text" class="val" name="val<?= "[$name][$k][]" ?>" value="" />
              </li>
            </ul>
          <? } else { ?>
            <input type="text" class="key" name="key<?= "[$name][$k]" ?>" value="<?= $k ?>" /> =
            <? if (strstr($u, "\n")) { ?> 
              <textarea name="val<?= "[$name][$k]" ?>"><?= $u ?></textarea>
            <? } else { ?>
              <input type="text" class="val" name="val<?= "[$name][$k]" ?>" value="<?= $u ?>" />
            <? } ?>
          <? } ?>
        </li>
      <? } ?>
      <li>
        <input type="text" class="key" name="key<?= "[$name][]" ?>" value="" /> =
        <input type="text" class="val" name="val<?= "[$name][]" ?>" value="" />
      </li>
    </ul>
  </li>
<? } ?>
</ul>
<input type="submit" value="<?= LANG_SAVE ?>" />
</form>
