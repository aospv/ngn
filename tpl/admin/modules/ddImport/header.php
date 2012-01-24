<div class="navSub iconsSet" id="subNav">
  <div class="navSubBtns">
    <a href="<?= Tt::getPath(1).'/pages/'.$d['page']['id'].'/editContent' ?>" class="edit"><i></i><b>Содержание</b></a>
    <a href="<?= Tt::getPath(3) ?>" class="upload"><i></i>Загурзить</a>
    <a href="<?= Tt::getPath(3).'/downloadXlsSample/sample_'.rand(0,1000).$d['page']['name'].'.xls' ?>" class="upload"><i></i>Скачать образец XLS-файла</a>
    <? if ($d['uploaded']) { ?>
      <a href="<?= Tt::getPath(3).'/import' ?>" class="import"><i></i>Импортировать</a>
      <a href="<?= Tt::getPath(3).'/delete' ?>" class="delete"><i></i>Удалить файл</a>
    <? } ?>
    <div class="clear"><!-- --></div>
  </div>
</div>
