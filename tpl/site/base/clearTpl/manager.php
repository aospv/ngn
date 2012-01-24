<script type="text/javascript" src="/m/js/wsi.js"></script>
<link rel="stylesheet" type="text/css" href="/m/css/wsi.css" media="screen, projection" />

<script>
(function() {
  new Ngn.Dialog.SupportChat({
    force: false,
    chatOptions: {
      wsUrl: 'ws://myninja.ru:8047/supportChat',
      selfName: 'masted',
      collocutorName: 'andrey',
      manager: true
    }
  });
  new Ngn.Dialog.SupportChat({
    force: false,
    chatOptions: {
      wsUrl: 'ws://myninja.ru:8047/supportChat',
      selfName: 'masted',
      collocutorName: 'client2',
      manager: true
    }
  });
}).delay(500);
</script>


<div class="wsi">
  <h1>Менеджер</h1>
  <p><input id="name" /> <input type="button" onclick="wsi.setName()" value="Change Name" /></p>
  <p><input id="text" /> <input type="button" onclick="wsi.send()" value="Send" /></p>
</div>
