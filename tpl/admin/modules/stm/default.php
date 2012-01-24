<? Tt::tpl('admin/modules/stm/header', $d) ?>

<ul id="stms">

<?

/* @var $v StmData */
foreach ($d['items'] as $v) {
  $link = '/?theme[location]='.$v->oSDS->options['location'].'&theme[design]='.
    $v->oSDS->options['design'].'&theme[n]='.$v->n;
    $a = $v->oSDS->options['location'].'/'.
         $v->oSDS->options['siteSet'].'/'.
         $v->oSDS->options['design'].'/'.
         $v->n;
  ?>
  <li class="item<?= $v->selected ? ' selected' : '' ?>">
    <? if (!$v->selected) { ?>
    <a href="<?= Tt::getPath(2).'/setTheme/'.$a ?>" class="btn"><span>Установить</span></a>
    <? } ?>
    <div class="tools">
      <a href="<?= $link ?>" target="_blank" 
        title="Предварительный просмотр" class="iconBtn preview"><i></i></a>
      <? if ($v->canEdit) { ?>
        <a href="<?= Tt::getPath(2).'/editTheme/'.$v->oSDS->options['location'].'/'.
          $v->oSDS->options['siteSet'].'/'.$v->oSDS->options['design'].'/'.
          $v->n
          ?>" title="Настройки темы" 
          class="iconBtn settings themeSettings"><i></i></a>
        <a href="<?= Tt::getPath(2).'/deleteTheme/'.$v->oSDS->options['location'].'/'.
          $v->oSDS->options['siteSet'].'/'.$v->oSDS->options['design'].'/'.
          $v->n
          ?>" title="Удалить тему" 
          class="iconBtn delete confirm"><i></i></a>
      <? } ?>
    </div>
    <h2><?= $v->data['data']['title'] ?></h2>
    <div class="gray designTitle"><small>Дизайн: <?= $v->oSDS->structure['title'] ?></small></div>
    <div class="clear"><!-- --></div>
    <div class="body">
      <iframe src="<?= $link ?>" scrolling="no"></iframe>
    </div>
    </li>
<? } ?>
</ul>

