<div class="search">
<form action="<?= Tt::getPath() ?>" method="get" id="searchForm">
  <input name="s" class="fld" value="<?= $d['s'] ?>" style="float: left; margin-right: 5px" />
  <a href="#" class="btn btn2" onclick="$('searchForm').submit(); return false;" style="width:100px; float: left;">Искать</a>
  <div class="clear"><!--  --></div>
</form>
<br />

<? if ($d['errors']) Tt::tpl('common/errors', $d['errors']) ?>
<? elseif ($d['results']) { ?>
  <p>Найдено: <b><?= $d['total'] ?></b></p>
  <? if ($d['pagination']['pNums']) { ?><div class="pNums pNumsTop"><?= $d['pagination']['pNums'] ?><div class="end2"><!-- --></div></div><? } ?>
  <div class="items">
  <? foreach ($d['results'] as $k => $v) {  ?>
    <div class="item">      
      <?
      if ($v['title']) $title = $v['title'];
      elseif ($v['name']) $title = $v['name'];
      else $title = null;
      ?>
      <b><a href="/<?= $v['pagePath'].'/'.$v['id'] ?>" target="_blank">
      <?= $title ? $title : $v['pagePath'].'/'.$v['id'] ?></a></b><br />
      <small class="gray"><?= $v['pageTitle'] ?></small>
      <? if ($v['parts']) { ?>
      <ul>
      <? foreach ($v['parts'] as $v2) { ?>
        <li><?= $v2 ?></li>
      <? } ?>
      </ul>
      <? } ?>
    </div>
  <? } ?>
  </div>
  <? if ($d['pagination']['pNums']) { ?><div class="pNums pNumsTop"><?= $d['pagination']['pNums'] ?><div class="end2"><!-- --></div></div><? } ?>

  <script type="text/javascript" src="./i/js/searchHighlight.js"></script>
  <script type="text/javascript">
  var words = new Array();
  <? foreach ($d['words'] as $word) { ?>
  //highlightSearchTerms('<?= $word ?>');
  <? } ?>
  </script>
<? } else { ?>
  <p class="info">Ничего не найдено</p>
<? } ?>
</div>