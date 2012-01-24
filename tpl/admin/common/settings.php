<?/*

Пример входных данных:

$d = array(
  'name' => 'settings_1',
  'structure' => array(
    'name1' => 'Имя 1',
    'limit' => 'Кол-во записей на странице'
  ),
  'values' => array(
    'name1' => 'такое вот имя',
    'limit' => 10
  )
);

*/?>
<form action="<?= Tt::getPath() ?>" method="post">
  <input type="hidden" name="action" value="setSettings" />
  <h2><?= LANG_COMMON ?></h2>
  <? Tt::tpl('common/settings', array(
    'structure' => $d['structure'],
    'values' => $d['values']
  )) ?>
  <p><input type="submit" value="<?= LANG_SAVE ?>" style="width:200px;height:30px;" /></p>
</form>