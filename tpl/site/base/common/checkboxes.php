<? foreach ($d['items'] as $k => $v) { ?>
  <label for="<?= $d['name'].$k ?>">
    <input type="checkbox" id="<?= $d['name'].$k ?>" name="<?= $d['name'] ?>[<?= $k ?>]" value="1"
    <?= @in_array($k, $d['checked']) ? 'checked' : '' ?> />&nbsp;<?= $v ?>
    <br />
  </label>
<? } ?>