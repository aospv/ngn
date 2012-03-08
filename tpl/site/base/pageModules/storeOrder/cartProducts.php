<?= SFLM::getCssTag('cpm/store/products.css') ?>
<table id="productsTable">
<tbody>
<? foreach ($d as $v) { ?>
<tr data-itemId="<?= $v['id'] ?>" data-pageId="<?= $v['pageId'] ?>">
  <td><a href="<?= $v['image'] ?>" class="thumb lightbox" target="_blank"><img src="<?= $v['sm_image'] ?>" /></a></td>
  <td width="100%"><a href="<?= DbModelCore::get('pages', $v['pageId'])->r['path'].'/'.$v['id'] ?>" target="_blank"><?= $v['title'] ?></a></td>
  <td nowrap class="cnt"><span class="cntV"><?= $v['cnt'] ?></span> шт.</td>
  <td nowrap><span class="priceV"><?= $v['price'] ?></span> руб.</td>
  <td class="iconsSet"><a href="#" class="delete tooltip" title="Убрать из корзины"><i></i></a></td>
</tr>
<? } ?>
</tbody>
<tfoot>
<tr class="total">
  <td colspan="4" nowrap>И того: <span class="priceV"><?= $v['price'] ?></span> руб.</td>
  <td></td>
</tr>
</tfoot>
</table>
<a href="#" class="btn" id="cartClean"><span>Очистить корзину</span></a>
<? if (!Auth::get('id')) { ?>
  <a href="#" class="btn btnAuth"><span>Авторизоваться</span></a>
<? } ?>
<script type="text/javascript">
new Ngn.cart.OrderList($('productsTable'), $('cartClean'));
</script>