<h2><?= $d['login'] ?></h2>
<? $i = UsersCore::getImageData($d['id']); ?>
<? if ($i['image']) { ?>
  <div class="avatarLarge"><img src="<?= $i['md_image'] ?>" /></div>
<? } ?>


