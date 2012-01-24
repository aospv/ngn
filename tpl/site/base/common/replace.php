<div class="searchBlock">
  <table cellpadding="0" cellspacing="0">
  <tr>
    <td>
      <p>Найти:</p>
      <input type="text" id="<?= $d['name'] ?>From" name="<?= $d['name'] ?>From"
        class="mask" style="width:200px;margin-right:5px;" />
    </td>
    <td>
      <p>Заменить на:</p>
      <input type="text" id="<?= $d['name'] ?>To" name="<?= $d['name'] ?>To"
        class="mask" style="width:200px;" />
    </td>
    <td style="padding-right:10px;">
      <p>&nbsp;</p>
      <a href="#" id="<?= $d['name'] ?>SearchBtn" class="searchBtn" title="Предпросмотр замены"></a>
    </td>
    <td>
      <p>&nbsp;</p>
      <input type="button" value="Заменить" id="<?= $d['name'] ?>ReplaceBtn" />
    </td>
  </tr>
  </table>
  <p><label for="<?= $d['name'] ?>IsRegexp"><input type="checkbox" name="isRegexp" value="1"
    id="<?= $d['name'] ?>IsRegexp" />
    Использовать регулярные выражения</label></p>
  <div class="clear"><!-- --></div>
  <div id="<?= $d['name'] ?>Results" class="results"></div>
</div>
<div class="preloadLoaderImage"></div>
<div class="clear"><!-- --></div>

<script type="text/javascript" src="./i/js/common/setSearch.js"></script>
<script type="text/javascript">
new Replace(
  '<?= Tt::getPath() ?>',
  $('<?= $d['name'] ?>SearchBtn'),
  $('<?= $d['name'] ?>ReplaceBtn'),
  $('<?= $d['name'] ?>Results'),
  $('<?= $d['name'] ?>From'),
  $('<?= $d['name'] ?>To'),
  $('<?= $d['name'] ?>IsRegexp'),
  'json_<?= $d['name'] ?>Search',
  'json_<?= $d['name'] ?>Replace'
);
</script>