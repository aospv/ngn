<?

if ($d['action'] == 'new' and !isset($_GET['editNotAllowed'])) {
  $items[] = '&bull; <a href="'.$_SERVER['REQUEST_URI'].'&editNotAllowed=1">Переключить в режим ограниченных прав</a>';
}

if (isset($items)) { 
?>
<div id="godPanel" class="adminPanel">
  <div class="adminPanelDrag"></div>
  <div class="adminPanelBody">
  <? foreach ($items as $item) print $item; ?>
  </div>
</div>
<script type="text/javascript">
new Ngn.GodPanel($('godPanel'));
</script>
<? } ?>