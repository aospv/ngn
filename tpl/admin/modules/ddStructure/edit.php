<? Tt::tpl('admin/modules/ddStructure/header') ?>

<? Tt::tpl('common/errors', $d['errors']) ?>

<form action="<?= Tt::getPath() ?>" method="post" id="strForm">

<div class="col">
  <input type="hidden" name="action" value="<?= $d['postAction'] ?>">
  <? if ($d['postAction'] == 'update') { ?><input type="hidden" name="id" value="<?= $d['id'] ?>"><? } ?>

  <p><b>Название структуры<span class="required">*</span>:</b></p>
  <input name="title" type="text" value="<?= $d['title'] ?>" class="required" style="width:300px;" maxlength="255" />

  <p><b>Имя структуры<span class="required">*</span>:</b></p>
  <input name="name" type="text" value="<?= $d['name'] ?>" class="required" style="width:300px;" maxlength="255" />
  <p style="margin-top:5px"><small>Только латинские символы</small></p>
  <p style="margin-top:15px"><input type="submit" value="<?= $d['action'] == 'edit' ? LANG_SAVE : LANG_CREATE ?>" style="width: 150px; height: 40px;"></p>
  </div>
  <div class="col">
  <p>
    <p><b>Тип структуры<span class="required">*</span>:</b></p>
    <?= Html::select('type', array(
      'dynamic' => 'Динамическая',
      'static' => 'Статическая',
      'variant' => 'Любая'
    ), $d['type'])
    ?>
  </p>
  <p>
    <small>Статические структуры используются для разделов, предполагающих только одну единственную запись. Например простой текстовый раздел, где страница - это одна запись.</small>
  </p>
  <hr />
  <p>
    <input name="locked" type="checkbox" value="1" id="locked"
    <?= $d['locked'] ? ' checked' : '' ?> />
    <label for="locked">структура с ограниченным доступом</label>
  </p>
  <p>
    <small>Для структур с ограниченным доступом в папку с файлами "u/strName" добавляется файл ".htaccess", запрещающий доступ к файлам.<br />
    Все файлы из этой папки получаются только через метод "action_getLockFile"</small>
  </p>
  <hr />
  <p>
    <input name="indx" type="checkbox" value="1" id="indx"
    <?= $d['indx'] ? ' checked' : '' ?> />
    <label for="indx">разрешить индексацию структуры</label>
  </p>
  <p>
    <small>Такие структуры, как, например, "Баннеры" не нуждаются в индексации, т.к. поиск по ним не нужен</small>
  </p>
</div>
<div class="col">
  <p><b><?= LANG_DESCRIPTION ?>:</b></p>
  <textarea name="descr" style="width:300px; height:150px;"><?= $d['descr'] ?></textarea>
</div>

</form>

<script type="text/javascript">
var myFormValidator = new FormValidator($('strForm'));
</script>
