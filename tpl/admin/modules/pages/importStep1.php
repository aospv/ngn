<? Tt::tpl('admin/modules/pages/header', $d) ?>

<form action="<?= Tt::getPath(4) ?>/importSaveFile" method="post" enctype="multipart/form-data">


  <? if (!$d['file']) { ?>
  <div class="info">
    <i></i>Файл для импорта не загружен
  </div>
  <? } else { ?>
  <div class="info">
    <i></i>Файл для импорта уже был загружен
    [инфа о файле]
    - формат
    - размер
  </div>
  
  <h2>Пример данных из этого файла</h2>
  <? Tt::tpl('admin/modules/pages/importSampleTable', $d['items']) ?>
  
  <? } ?>
  <br />
  <input type="button" value="Перейти к шагу 2" onclick="window.location='<?= Tt::getPath(4) ?>/importStep2'" />
  <hr />
  <h2>Загрузить новый</h2>
  <p>
    <?= LANG_FILE ?> <input type="file" name="file" />
    <input type="submit" value="Загрузить" />
  </p>
</form>