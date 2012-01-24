<div id="contentArea">
<?= $d['content'] ?>
</div>

<? if ($d['priv']['edit']) { ?>
  <a href="#" id="editCBtn">ред.</a>


  <script type="text/javascript" src="./i/js/tiny_mce/tiny_mce.js"></script>
  <script type="text/javascript" src="./i/js/tiniInit.js"></script>
  <script type="text/javascript">
  new TiniInitContent('<?= Tt::getPath() ?>', 'ajaxGetContent', 'editCBtn', 'contentArea', 'contentEdit');
  </script>
<? } ?>
