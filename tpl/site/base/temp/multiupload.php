<?= SFLM::getJsTags('upload') ?>

<link rel="stylesheet" href="./i/css/common/upload.css" />
<script>

getBase();

window.addEvent('domready', function() { // wait for the content
  Ngn.uploadAttache('demo-list', '#demo-attach, #demo-attach-2', {
    url: 'http://dev.baby-nn.ru:8080/c/panel/?a=ajax_multiupload',
    onFileComplete: function(o) {
      JSON.decode(o.response.text)
    }
  });
});
</script>

<a href="#" id="demo-attach">Attach a file</a>
 
<ul id="demo-list"></ul>
 
<a href="#" id="demo-attach-2" style="display: none;">Attach another file</a>