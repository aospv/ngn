<html>
<body style="margin:0px;">
  <div style="background:<?= $d['color'] ?>;padding:3px;">
    <b><?= $d['title']?></b>:
    <a href="/<?= Tt::getUserPath($d['item']['userId']) ?>"><?= $d['item']['login'] ?></a>: 
    <a href="<?= $d['item']['link'] ?>" style="color:#000000">
      <?= Misc::cut($d['item']['text'], 80).(((time() - $d['item']['dateCreate_tStamp']) < 5184000) ? '</a> ('.date('H:i', $d['item']['dateCreate_tStamp']).')' : '') ?>
  </div>
  <script>
  function refrashpage() {
    window.location = window.location;
  }
  setTimeout("refrashpage()", 30000);
  </script>
</body>
</html>