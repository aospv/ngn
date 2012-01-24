<form action="<?= Tt::getPath() ?>?a=addModer" method="post">
<div class="userSearch">
  <input type="hidden" name="pageId" value="<?= $_input['pageId'] ?>" />
  <input type="text" id="userMask" name="userSearch" value="" class="fld" style="margin: 0px" />
  <a href="#" id="userSearchBtn"></a>
  <div class="clear"><!-- --></div>
  <div id="results" style="min-height: 16px; min-width: 16px;"></div>
</div>
</form>
<div class="preloadLoaderImage"></div>
<div class="clear"><!-- --></div>

<script type="text/javascript">
var url = '<?= Tt::getPath() ?>';
var userSearchBtn = $('userSearchBtn');
userSearchBtn.addEvent('click', function(e) {
  new Event(e).stop();
  var userMask = $('userMask').getProperty('value');
  var results = $('results');
  if (!userMask) return;
  results.addClass('loader');
  new Request({
    url: url,
    onComplete: function(data) {
      results.set('html', data);
      results.removeClass('loader');
    }
  }).POST({
    'action' : 'ajax_searchUser',
    'userMask' : userMask
  });
});
</script>
