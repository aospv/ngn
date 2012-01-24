  <? if ($d['path']) { ?>
    <div class="pagePath">
      <? if (isset($d['pagination'])) Tt::tpl('admin/common/pnums', $d['pagination']) ?>
      <? if (isset($d['filter'])) { ?>
      <div class="itemsFilter">
        <div class="iconsSet icon_filter"><i></i><?= $d['filter']['title'] ?>:</div>
        <?= Html::select($d['filter']['name'], $d['filter']['options'], $d['filter']['selected'], array('tagId' => 'filter_'.$d['filter']['name'])) ?>
      </div>
      <script type="text/javascript">
      var eFilter = $('filter_<?= $d['filter']['name'] ?>');
      eFilter.addEvent('change', function(e) {
        if (this.get('value')) {
          window.location = Ngn.getPath(4) + '/' + '<?= $d['filter']['param'] ?>' + '.' + 
            this.get('name') + '.' + eFilter.get('value');
        } else {
          window.location = Ngn.getPath(4);
        }
      });
      </script>
      <? } ?>
      <div class="cont">
        <?= Tt::enumDddd($d['path'], '`<a href="`.$link.`" class="`.$name.`">`.$title.`</a>`', ' → ') ?>
      </div>
      <div class="clear"><!-- --></div>
    </div>
  <? } else { ?>
    <div class="pagePathDummy"></div>
  <? } ?>
  