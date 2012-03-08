<? Tt::tpl('admin/modules/stm/header', $d) ?>
<style>
.items {
background-color: #BFDCFF;
padding: 10px;
}
.items .item {
border-bottom: 1px solid #555555;
}
.items .iconBtn {
float: left;
margin: 0px 5px 0px 0px;
}
.items h2 {
font-size: 18px;
}

/*
#menu li ul {
display: block !important;
}
.am_stm #menus li {
margin: 0px;
}
*/
.am_stm #menus {
line-height: 1.5;
}
</style>

<?php /*
<link rel="stylesheet" type="text/css" 
href="<?= SFLM::getCachedUrl('s2/css/common/horizontalMenu.css?ids=menu') ?>" media="screen, projection" />
*/?>

<link rel="stylesheet" type="text/css" 
href="./i/css/common/hMenu.css" media="screen, projection" />

<div class="items" id="menus">
<?

$i = 0;
/* @var $v StmData */
foreach ($d['items'] as $v) {
  $i++;
  $o = new StmMenuCss($v);
  ?>

  <style>
  <?= str_replace('.hMenu', '.hMenu.custom'.$i, $o->oCss->css) ?>
  </style>
  
  <div class="item">
    <? if ($v->canEdit) { ?> 
      <a href="<?= Tt::getPath(2).'/editMenu/'.$v->oSDS->options['location'].'/'.
        $v->oSDS->options['menuType'].'/'.$v->n ?>"
        class="iconBtn edit" title="Редактировать меню"><i></i></a>
      <a href="<?= Tt::getPath(2).'/deleteMenu/'.$v->oSDS->options['location'].'/'.
        $v->oSDS->options['menuType'].'/'.$v->n ?>"
        class="iconBtn delete confirm" title="Удалить меню"><i></i></a>
    <? } else { ?>
      <a href="" class="iconBtn dummy"><i></i></a>
    <? } ?>
    <h2><?= $v->oSDS->structure['title'].' / <b>'.$v->data['title'].'</b>' ?></h2>
    <div class="hMenu custom<?= $i ?>">
      <?
      $oMenu = Menu::getUlObj('main', 2, '`<a href="`.$link.`"><span>`.$title.`</span></a><i></i><div class="clear"></div>`');
      $oMenu->setCurrentId(2);
      print $oMenu->html();
      ?>
      <div class="clear"><!-- --></div>
    </div>
  </div>

<? } ?>
</div>

<script type="text/javascript">
$('menus').getElements('.item').each(function(el){
  el.getElement('.hMenu').getElements('a').each(function(eA){
    eA.addEvent('click', function(e){
      e.preventDefault();
    });
  });
  new Ngn.HorizontalMenuRoundedBg(el.getElement('.hMenu'), {
    createExtraElements: false,
    borderElement: $('container'),
    borderPlus: 8
  });
});
</script>