<? Tt::tpl('admin/modules/tags/header', $d) ?>
<form action="<?= Tt::getPath() ?>" method="post" style="width:600px;">
  <input type="hidden" name="action" value="makeImport" />
  <table width="100%">
  <tr>
    <td><textarea name="text" style="width:300px;height:200px;"></textarea></td>
    <td valign="top" style="padding-left:10px;" width="100%">
<? if ($d['tree']) { ?>
  <p>Пример заполнения:</p>
  <pre>
- Тэг 1
- Тэг 2  
- - Подтэг 1
- - Подтэг 2
- Тэг 3
  </pre>
<? } else { ?>
  <p><b>Разделитель:</b></p>
  <p>
    <input type="radio" name="sep" value="quote" checked /> запятая    
    <input type="radio" name="sep" value="br" /> перенос строки    
  </p>
  <p><b>Пример заполнения:</b></p>
  <pre>Тэг 1, Тэг 2, Тэг 3</pre>
<? } ?>    
    </td>
  </tr>
  </table>

    <input type="submit" value="Импортировать" style="width:150px;height:30px;float:left;" />
    <div class="saveAndReturn">
      <label for="deleteBeforeImport">
        <input type="checkbox" id="deleteBeforeImport" value="1" name="deleteBeforeImport">
        <small>удалить все теги перед импортированием</small></label>
    </div>
</form>