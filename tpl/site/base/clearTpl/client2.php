<script type="text/javascript" src="/m/js/wsi.js"></script>
<link rel="stylesheet" type="text/css" href="/m/css/wsi.css" media="screen, projection" />

<script>
(function() {
  new Ngn.Dialog.SupportChat({
    chatOptions: {
      wsUrl: 'ws://myninja.ru:8047/supportChat',
      selfName: 'client2',
      collocutorName: 'masted'
    }
  });
}).delay(500);
</script>

<div class="wsi">
  <h1>Клиент</h1>
  <select></select>
  <p><input id="name" /> <input type="button" onclick="wsi.setName()" value="Change Name" /></p>
  <p><input id="text" /> <input type="button" onclick="wsi.send()" value="Send" /></p>
</div>
