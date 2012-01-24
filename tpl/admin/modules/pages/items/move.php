<? if (isset($d['itemIds'])) { ?>
  <h2>Перемещение группы записей в количестве <b><?= count($d['itemIds']) ?></b> шт.
  <? if (isset($d['toPageData'])) { ?>в раздел <b><?= $d['toPageData']['title'] ?></b><? } ?>
  </h2>
<? } else { ?>
  <h2>Перемещение записи <b><?= $d['item']['title'] ?></b>
  <? if (isset($d['toPageData'])) { ?>в раздел <b><?= $d['toPageData']['title'] ?></b><? } ?>
  </h2>
<? } ?>


<form action="<?= Tt::getPath() ?>" method="POST">
  <input type="hidden" name="action" value="<?= $d['postAction'] ?>" />
  <? if (isset($d['itemIds'])) { ?>
    <? foreach ($d['itemIds'] as $id) { ?>
      <input type="hidden" name="itemIds[]" value="<?= $id ?>" />
    <? } ?>
  <? } else { ?>
    <input type="hidden" name="itemId" value="<?= $d['item']['id'] ?>" />
  <? } ?>
  <? if (!isset($d['toPageId'])) { ?>
    <p class="info" style="width:600px">
    Данная функция используется для переноса записей из раздела в раздел.<br />В том случае, если разделы используют различные структуры, при переносе возможна потеря данных несоответствующих полей.</p>
  
    <p><b>Найдите и выберите раздел сайта:</b></p>
    <p><? Tt::tpl('common/autocompleter', array(
  'name' => 'pageId',
  'actionKey' => 'page'.ucfirst($d['page']['controller'])
    )) ?></p>
  <? } else { ?>
    <input type="hidden" name="pageId" value="<?= $d['toPageId'] ?>" />
  <? } ?>
  <? if (isset($d['conformance'])) { ?>
    <p class="error">Ниже указаны поля, которые будут перенесены успешно.<br />
    Поля, имена которых одинаковы в обеих структурах: той из которой переносится запись и той в которую переносится.<br />
    <b>Внимательно проследите, что бы все необходимые для переноса поля присутствовали в списке</b>
    </p>
    <ul>
    <? foreach ($d['conformance'] as $v) { ?>
      <li><?= $v ?></li>
    <? } ?>
    </ul>
  <? } ?>
  <p><input type="submit" value="Переместить" style="width:200px;height:25px;" /></p>
</form>