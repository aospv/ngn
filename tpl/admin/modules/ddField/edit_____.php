<? $fd = $d['field'] ?>

<? Tt::tpl('admin/modules/ddField/header', $d) ?>
<? Tt::tpl('common/errors', $d['errors']) ?>

<style>
#typeData select {
width: 200px;
}
.cols .col {
width: 250px;
}
input[name=title], input[name=def], textarea {
width: 100%;
}
textarea {
height: 50px;
}
.cols .col p {
margin-bottom: 5px;
}
</style>


<form action="<?= Tt::getPath() ?>" method="post" id="ddFieldForm">
<div class="cols">
<div class="col" >
<input type="hidden" name="action" value="<?= $d['postAction'] ?>">
<? if ($d['postAction'] == 'update') { ?><input type="hidden" name="id" value="<?= $fd['id'] ?>"><? } ?>

<p><b>Название<span class="required">*</span>:</b></p>
<input name="title" value="<?= $fd['title'] ?>" maxlength="255">

<? if (!$fd['typeData']['virtual']) { ?>
<div class="col" style="width:190px;">
  <p><b>Имя поля<span class="required">*</span>:</b></p>
  <p style="margin-top:5px"><small>Используется при создании БД<br />
  <span class="alert">Только латинские символы!</span></small></p>
  <input name="name" value="<?= $fd['name'] ?>" style="width:100%;" maxlength="50">
</div>
<? } ?>
<div class="col" style="width:50px;">
  <p><b># п.п.:</b></p>
  <input name="oid" value="<?= $fd['oid'] ?>" style="width:50px;" maxlength="10">
</div>
<div class="clear"><!-- --></div>

<p><b>Значение по умолчанию:</b></p>

<?=
$list ? 
  Html::select('default', $list, 1, $fd['default']) :
  '<input name="default" value="'.$fd['default'].'" maxlength="255">';
?>

<p><b>Описание:</b></p>
<p style="margin-top:5px"><small><b>Заполнять необязательно</b><br />Будет выводится справа или под полем</small></p>
<textarea name="descr"><?= $fd['descr'] ?></textarea>

<p><b>Максимальная длина:</b></p>
<input name="maxlength" value="<?= $fd["maxlength"] ?>" style="width:50px;" maxlength="5">
<p style="margin-top:5px"><small>Оставьте 0, если максимальная длина не нужна</small></p>

</div>
<? if (!$fd['typeData']['virtual']) { ?>
<div class="col">
  <p><b>Тип<span class="required">*</span>:</b></p>
  <table cellpadding="0" cellspacing="0" id="itemsTable">
  <? foreach ($d['types'] as $k => $v) { if ($v['virtual']) continue; ?>
  <tr>
    <td><input type="radio" name="type" value="<?= $k ?>"<?= ($fd['type'] == $k ? ' checked' : '') ?>></td>
    <td><img src="<?= DdFieldCore::getIconPath($v['name']) ?>"></td>
    <td><?= $v["title"] ?></td>
  </tr>
  <? } ?>
</table>
</div>
<? } ?>
<div class="col">

<div id="dfp">
  <?= $d['dfpHtml'] ?>
</div>

<? /* @todo делать различную инициализацию для "new" и "edit" */?>

<script type="text/javascript" src="./i/js/ngn/Ngn.cp.Dfp.js"></script>
<script type="text/javascript">
window.addEvent('domready', function() {
  var dfp = new Ngn.cp.Dfp({
    fieldId: <?= (int)$fd['id'] ?>,
    init_list: function(fieldName) {
      new Ngn.frm.FieldSet('listEdit');
    },
    init_tagsFlat: function(fieldName) {
      new Ngn.frm.FieldSet('listEdit');
    },
    init_tagsTree: function(fieldName) {
      $('treeContainer').addClass('loader');
      new Ngn.Lib('mif.tree', function() {
        Asset.javascript('../../../i/js/ngn/Ngn.TreeEditTags.js', {onload: function(){
          $('treeContainer').removeClass('loader');
          new Ngn.TreeEditTags(
            'treeContainer',
            'treeMenu',
            '<?= $fd['strName'] ?>',
            fieldName, // groupName
            {
              actionUrl: '<?= Tt::getPath(1).'/tags' ?>'
            }
          );
        }});
      });
    }
  });
  <? if ($fd['dfpType']) { // Инициализируем только при наличии DFP-типа ?>
    dfp.initDfp('<?= $fd['dfpType'] ?>', '<?= $fd['name'] ?>', <?= $fd['id'] ?>);
  <? } ?>
});
</script>

<p>
  <input name="notList" type="checkbox" value="1" id="notList"
  <?= $fd['notList'] ? ' checked' : '' ?> />
  <label for="notList">не выводить поле в списках</label>
</p>

<p>
  <label for="defaultDisallow"><input type="checkbox" name="defaultDisallow" 
    id="defaultDisallow" value="1" <?= $fd['defaultDisallow'] ? 'checked' : '' ?> /> не доступно по умолчанию</label>
  <br />
  <small>Используется в том случае, если поле отображается только при необходимых привилегиях</small>
</p>

<p>
  <label for="system"><input type="checkbox" name="system" 
    id="system" value="1" <?= $fd['system'] ? 'checked' : '' ?> /> системное</label>
  <br />
  <small>Используется в том случае, если изменение пользователем этого поля не предполагается</small>
</p>

<p>
  <label for="required"><input type="checkbox" name="required" 
    id="required" value="1" <?= $fd['required'] ? 'checked' : '' ?> /> обязательно для заполнения</label>
</p>

<br />

<input type="submit" value="Сохранить" class="btn" style="width:200px;height:50px;">
<p>
  <label for="saveAndReturn"><input type="checkbox" name="saveAndReturn" 
    value="1" id="saveAndReturn" /> <small>возвратиться на эту форму после сохранения</small></label>
</p>

</div>
<div class="clear"><!-- --></div>
</div>
</form>
