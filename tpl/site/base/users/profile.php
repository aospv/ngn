<? if ($d['priv']['edit']) { ?>
  <? if ($d['data']) { $ud =& $d['data'] ?>
    <p style="float:right"><a href="?a=edit">Изменить</a></p>
  <? } else { ?>
    <p>Данные не заполнены. <a href="?a=new">Заполнить?</a></p>
  <? } ?>
<? } ?>


<h2>Профиль пользователя <?= $d['user']['login'] ?></h2>

<? if ($d['data']['image']) { ?>
  <img src="<?= $d['data']['md_image'] ?>" />
<? } ?>

<? Tt::tpl('common/dataTable', array(
  'titles' => array(
    'name' => 'Имя',
    'city' => 'Город',
    'url' => 'URL',
    'interests' => 'Интересы',
    'icq' => 'ICQ'
  ),
  'items' => $d['data'] 
)) ?>
