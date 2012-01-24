<? $id = Misc::name2id($d['name']) ?>
<? if (!$d['actionKey']) $d['actionKey'] = $d['name']; ?>
<input type="text" class="fld" id="ac-<?= $id ?>" value="<?= $d['acDefault'] ?>" title="<?= $d['actionKey'] ?>" />
<input type="text" style="display:none" name="<?= $d['name'] ?>" id="fld-<?= $id ?>" value="<?= $d['default'] ?>" />
