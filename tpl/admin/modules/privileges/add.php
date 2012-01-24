<? Tt::tpl('admin/modules/privileges/header', $d) ?>

<h2>Назначение привилегий</h2>
<p>
  Здесь вы можете назначить любому пользователю определённые привилегии
  для каждого раздела сайта.
</p>

<form action="<?= Tt::getPath(2) ?>" method="POST" id="privForm">
  <input type="hidden" name="action" value="create" />
  <div class="cols">
    <div class="col">
      <div class="row">
        <p><b>Найдите и выберите пользователя:</b></p>
        <? Tt::tpl('common/autocompleter', array('name' => 'userId', 'actionKey' => 'user')) ?>
      </div>
      <div class="row">
        <p><b>Найдите и выберите раздел сайта:</b></p>
        <? Tt::tpl('common/autocompleter', array(
          'name' => 'pageId',
          'actionKey' => 'page',
          'acDefault' => $d['page']['title'],
          'default' => $d['page']['id']
        )) ?>
      </div>
    </div>
    <div class="col">
      <p><b>Привелегии:</b></p>
      <? Tt::tpl('common/checkboxes', array(
        'name' => 'types',
        'checked' => array(),
        'items' => $d['types']
      )) ?>
      <p><input type="submit" value="Назначить" style="width:150px; height:30px;" /></p>
    </div>
  </div>
</form>

<script type="text/javascript">

var checkboxesChecked = function(eForm, name) {
  var elements = eForm.getElements('input[name^=' + name + ']');
  for (var i=0; i < elements.length; i++)
    if (elements[i].get('checked')) return true;
  return false;
}

var ePrivForm = $('privForm');
Ngn.cp.addFormValidation(ePrivForm, function() {
  if (!$('fld-userId') || !$('fld-userId').get('value')) {
    alert('Найдите и выберите пользователя');
    return false;
  }
  if (!$('fld-pageId') || !$('fld-pageId').get('value')) {
    alert('Найдите и выберите раздел сайта');
    return false;
  }
  if (!checkboxesChecked(ePrivForm, 'types')) {
    alert('Выберите привилегии');
    return false;
  }
  return true;
});

</script>

