<script type="text/javascript" src="./i/js/moo.js"></script>
<script type="text/javascript">
var loadSubjects = function(obj) {
  var subjId = obj.getProperty('id');
  var url = '<?= getLink() ?>?a=ajaxGetForums&subjId=' + subjId;
  new Ajax(url, {
    method: 'get',
    update: obj,
    onComplete: function() {
      obj.getElements('a').each(function(item){
        item.addEvent('click', function(e){
          e.preventDefault();
          var forumId = item.getProperty('id');
          window.location = '<?= getLink() ?>?a=moveSubj&subjId='+subjId+'&forumId='+forumId;
        });
      });
    }
  }).request();
}
initPopups(loadSubjects);
</script>
