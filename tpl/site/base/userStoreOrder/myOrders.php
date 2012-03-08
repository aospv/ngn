<?= SFLM::getCssTag('cpm/userStore/products.css') ?>
<div class="myOrders">
<table id="productsTable" class="valign">
<? $num = 0; foreach ($d['items'] as $v) { $num++; ?>
<tr>
  <th colspan="2">Заказ №<?= $num ?>. Товары:</th>
  <th class="iconsSet"><a href="#" class="delete tooltip" data-id="<?= $v['id'] ?>" data-pageId="<?= $i['pageId'] ?>" title="Удалить заказ"><i></i></a></th>
</tr>
<tr>
<td colspan="3" class="products">
  <table>
  <?
  $total = 0;
  foreach ($v['items'] as $i) {
    $i = $i['item']; ?>
  <tr>
    <td><a href="<?= $i['image'] ?>" class="thumb lightbox" target="_blank"><img src="<?= $i['sm_image'] ?>" /></a></td>
    <td><?= $i['title'] ?></td>
    <td nowrap><?= $i['price'] ?> руб.</td>
  </tr>
  <?
    $total += $i['price'];
  }
  ?>
  <? if (count($v['items']) > 1) { ?>
  <tr class="totalRow">
    <td></td>
    <td class="totalTitle">Итого:</td>
    <td><?= $total ?> руб.</td>
  </tr>
  <? } ?>
  </table>
</td>
</tr>
<tr>
  <th>Данные покупателя:</th>
  <th>Создан:</th>
</tr>
<tr>
  <td><? Tt::tpl('common/titledTable', $v['data']) ?></td>
  <td nowrap><small><?= datetimeStr(strtotime($v['dateCreate'])) ?></small></td>
</tr>
<tr><td colspan="3"><hr /></td></tr>
  <? } ?>
</table>
</div>
<script type="text/javascript">
$('productsTable').getElements('.delete').each(function(eBtn) {
  eBtn.addEvent('click', function(e){
    e.preventDefault();
    if (!Ngn.confirm()) return;
    new Request({
      url: '/userStoreMyOrders/ajax_delete',
      onComplete: function() {
        window.location.reload(true);
      }
    }).get({
      id: eBtn.get('data-id')
    });
  });
});
</script>