<? Tt::tpl('admin/modules/ddField/header') ?>
<div class="col" style="width:400px;">
  <p>Введите здесь структуру полей в текстовом виде:</p>
  <textarea id="text" name="text" style="width:100%; height:200px;margin-bottom:7px"></textarea>
  <input type="button" value="Импортировать" id="btnImport" style="width:150px;height:30px;" />
  <!-- <input type="button" value="Предпросмотр" id="btnPreview" style="width:150px;height:30px;" /> -->
</div>
<div class="col" style="width:400px;">
  <p>Предварительный просмотр:</p>
  <div style="border:1px solid #CCCCCC; padding: 5px 10px 5px 10px;">
    <div id="results" style="min-height:200px"></div>
  </div>
</div>

<script type="text/javascript">
var eResults = $('results');
var fldSubmit = function(eFld, eBtn, eResults, ajaxAction) {
  eBtn.addEvent('click', function() {
    eResults.addClass('loader');
    if (!eFld.get('name')) alert("eFld.get('name') not defined");
    var post = new Hash();
    post[eFld.get('name')] = eFld.get('value');
    new Request({
      url: window.location.pathname + '?action=ajax_' + ajaxAction,
      onComplete: function(html) {
        eResults.set('html', html);
        eResults.removeClass('loader');
      }.bind(this)
    }).POST(post);  
  });
}
var eText = $('text');
var progress = false;
var post = new Hash();
eText.addEvent('keydown', function(e) {
  if (progress) return;
  progress = true;
  post[eText.get('name')] = eText.get('value');
  new Request({
    url: window.location.pathname + '?action=ajax_importPreview',
    onComplete: function(html) {
      eResults.set('html', html);
      progress = false;
    }.bind(this)
  }).POST(post);  
});

fldSubmit(eText, $('btnImport'), eResults, 'importMake');
//fldSubmit(eText, $('btnPreview'), eResults, 'importPreview');
</script>