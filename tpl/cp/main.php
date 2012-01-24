<? Tt::tpl($d['name'].'/head', $d) ?>
<body>
<table cellpadding="0" cellspacing="0" height="100%" width="100%" id="body">
<tr><td height="100%" valign="top">
<div class="admin">
  <div id="top">
    <div id="header">
      <table cellpadding="0" cellspacing="0" width="100%" class="valignSimple">
      <tr>
        <td>
          <div class="logo"><a href="<?= Tt::getPath(1) ?>"><img src="./i/img/ngn/logo.gif" title="Перейти на главную страницу панели управления"></a></div>
        </td>
        <td>
        <div class="auth">
          <div class="cont">
            <? Tt::tpl('admin/loginInfo', $d) ?>
          </div>
        </div>
        </td>
        <td width="100%">
          <div class="pageTitle">
            <div class="cont">
              <h1><?= $d['moduleTitle'] ?></h1>
              <h2><?= $d['pageTitle'] ?></h2>
            </div>
          </div>
        </td>
      </tr>
      </table>
    </div>
    <div class="navTop iconsSet">
      <? Tt::tpl('cp/links', $d['topLinks']) ?>
      <div class="clear"><!-- --></div>
    </div>
    <? Tt::tpl('cp/path', $d) ?>
  </div>
  <div class="<?= $d['mainContentCssClass'] ?>" id="mainContent">
    <? Tt::tpl($d['tpl'], $d) ?>
    <div class="clear"><!-- --></div>
  </div>
</div>
</td></tr><tr><td>
  <div id="bottom">
    <div class="cont">
      <a target="_blank" class="smallLogo" title="MyNinja. сервис создания сайтов"></a>
      <div class="copy"><?= AdminCore::getCopyright()?></div>
    </div>
  </div>
</td></tr></table>
</body>
</html>
