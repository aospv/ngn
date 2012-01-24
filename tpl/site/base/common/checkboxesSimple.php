<? $i = 0; foreach ($d['items'] as $k => $v) { ?>
  <label for="<?= $d['name'].$k ?>">
    <input type="checkbox" id="<?= $d['name'].$k ?>"
      name="<?= $d['name']."[$i]" ?>" value="<?= $k ?>"
    <?= (is_array($d['checked']) and in_array($k, $d['checked'])) ? 'checked' : '' ?> />
    <?= $v ?>
    <br />
  </label>
<? $i++; } ?>