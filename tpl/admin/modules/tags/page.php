<p class="path"><small><?= $d['path'] ?></small></p>

<h2>Поля с тэгами раздела <b>«<?= $d['page']['title'] ?>»</b></h2>
<? if ($d['tagFields']) { ?>
  <p>Следующие поля имеют <b>тэги</b></p>
  <p>Выберите одно из них для редактирования тэгов:</p>
  <div class="iconsSet large" style="margin-top:15px">
  <? foreach ($d['tagFields'] as $k => $v) { ?>
    <a href="<?= Tt::getPath() ?>/<?= $v['name'] ?>" class="tags"><i></i><?= $v['title'] ?></a>
    <div class="clear"><!-- --></div>
  <? } ?>
  </div>
<? } else { ?>
  Этот раздел не имеет полей с тэгами.
<? } ?>
