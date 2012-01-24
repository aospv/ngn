<? Tt::tpl('head', $d); ?>

<body>
<div id="layout" class="layout_<?= $d['layoutN'] ?><?= $d['page']['module'] ? ' pm_'.$d['page']['module'] : '' ?><?= $d['page']['home'] ? ' home' : '' ?> pn_<?= $d['page']['name'] ?> action_<?= $d['action'] ?><?= $d['settings']['defaultAction'] == 'blocks' ? ' blocksLayout' : '' ?>">
<div class="lwrapper">
  <div class="container">
    <?= StmCore::slices() ?>
    <a href="/" id="logo" title="<?= SITE_TITLE ?>"><span><?= SITE_TITLE ?></span></a>
    <? if (SiteLayout::topEnabled()) { ?>
    <div class="span-24 last" id="top">
      <?= Html::baseDomainLinks(Tt::getTpl('top', $d)) ?>
    </div>
    <? } ?>
    <div class="span-24 last">
      <div id="col2body">
        <div id="col2nav">
          <div class="hMenu mainmenu" id="menu">
            <?= SiteLayout::menu($d['oController']) ?>
            <div class="clear"><!-- --></div>
          </div>
        </div>
        <!-- Page Layout Begin -->
        <?
        if (!empty($d['oController']->userGroup)) {
          print Html::subDomainLinks(Tt::getTpl('pageLayout/'.$d['layoutN'], $d), $d['oController']->userGroup['name']);
        } else {
          print Html::baseDomainLinks(Tt::getTpl('pageLayout/'.$d['layoutN'], $d));
        }
        ?>
        <!-- Page Layout End -->
      </div>
    </div>
  </div>
  <div class="push"></div>
</div>
</div>

<div id="footer">
  <div class="fContainer">
    <div class="body">
      <?= Slice::html('footer', 'Подвал') ?>
    </div>
  </div>
</div>

<script type="text/javascript">
Ngn.site.page = <?= Arr::jsObj($d['page']->r) ?>;
</script>

</body> 
</html>
