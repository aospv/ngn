<!-- Начало заголовочного блока -->
<div id="pageTitle">
  <div class="iconsSet">
  <?
  if (!empty($d['topBtns'])) {
    foreach ($d['topBtns'] as $v) {
      print '<a href="'.(empty($v['link']) ? '#' : $v['link']).'" rel="nofollow" class="btn btn1'.
        (empty($v['class']) ? '' : ' '.$v['class']).'"'.
        (empty($v['id']) ? '' : ' id="'.$v['id'].'"').'><span>'.
        $v['title'].'</span></a>';
    }
  }
  ?>
  </div>
  
  <? /*if (Config::getVarVar('layout', 'enableShareButton') and isset($d['item'])) { ?>
  <script type="text/javascript" src="http://vkontakte.ru/js/api/share.js?11" charset="windows-1251"></script>
  <div style="float:right;padding-top:3px;">
  <script type="text/javascript">
    document.write(VK.Share.button(
      <?
      $i['image'] = isset($d['item']['image']) ? str_replace('./', '/', $d['item']['image']) : '';
      $i['title'] = $d['pageTitle'];
      $i['description'] = isset($d['item']['text']) ? Misc::cut($d['item']['text'], 200) : '';
      //$i['noparse'] = true;
      ?>
    <?= Arr::jsObj($i) ?>,{type: "round", text: "Сохранить"}));
  </script>
  </div>
  <? }*/ ?>

  <div class="pNums">
    <?= Html::addParam($d['pagination']['pNums'], 'class', 'btn', array('a', 'b')) ?>
  </div>
  <? if ($d['pagination']['itemsTotal'] and 0) { ?>
    <div style="float:right;padding: 2px 10px 0px 0px;" class="dgray">
    <? if ($d['page']['controller'] == 'searcher') { ?>
      Найдено <b><?= $d['pagination']['itemsTotal'] ?></b> записей
    <? } ?>
    </div>
  <? } ?>
  <h1><?= $d['pageTitle'] ?></h1>
</div>
