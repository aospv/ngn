<?= SFLM::getCssTag('cpm/userStore/products.css') ?>
<table id="productsTable">
<? foreach ($d as $_v) { $v = $_v['item']; ?>
<tr>
  <td><a href="<?= $v['image'] ?>" class="thumb lightbox" target="_blank"><img src="<?= $v['sm_image'] ?>" /></a></td>
  <td width="100%"><a href="<?= DbModelCore::get('pages', $v['pageId'])->r['path'] ?>" target="_blank"><?= $v['title'] ?></a></td>
  <td nowrap><?= $v['price'] ?> руб.</td>
  <td class="iconsSet"><? if (count($d) > 1) { ?><a href="#" class="delete tooltip" data-itemId="<?= $v['id'] ?>" data-pageId="<?= $v['pageId'] ?>" title="Убрать из корзины"><i></i></a><? } ?></td>
</tr>
<? } ?>
</table>
<script type="text/javascript">
$('productsTable').getElements('.delete').each(function(eBtn) {
  eBtn.addEvent('click', function(e){
    e.preventDefault();
    if (!Ngn.confirm()) return;
    new Request({
      url: '/c/storeCart/ajax_delete',
      onComplete: function() {
        window.location = window.location;
      }
    }).get({
      itemId: eBtn.get('data-itemId'),
      pageId: eBtn.get('data-pageId')
    });
  });
});
</script>