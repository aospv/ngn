<style>
.subscribedItems li {
list-style: none;
margin: 0px;
}
.subscribedItems ul {
margin: 0px;
}
.subscribedItems {
padding: 3px 0px 5px 25px;
}
</style>

<? if (Auth::get('id')) { ?>
  <form action="<?= Tt::getPath() ?>" method="POST">
  <input type="hidden" name="action" value="update" />
  <? foreach ($d['types'] as $k => $v) { ?>
  <label for="<?= $k ?>">
    <input type="checkbox" id="<?= $k ?>" name="types[<?= $k ?>]" value="1"
    <?= in_array($k, $d['userTypes']) ? 'checked' : '' ?> />&nbsp;
    <?= (!empty($d['subItems_'.$k]) ? 
      '<a href="#" id="btn_'.$k.'">↓ '.$v.'</a>' : $v) ?>
    <br />
    </label>
    
    <? if (!empty($d['subItems_'.$k])) { ?>
      <div id="si_<?= $k ?>" class="subscribedItems">
      <ul>
        <? foreach ($d['subItems_'.$k] as $v) { // prr($v) ?>
          <li>
            <div class="smIcons bordered">
              <a href="<?= Tt::getPath(1).'?a=delete_'.$k.'&pageId='.$v['pageId'].($v['itemId'] ? '&itemId='.$v['itemId'] : '') ?>" class="sm-delete" title="Отписаться"><i></i></a>
            </div>
            <?= '<a href="'.$v['pagePath'].'" target="_blank">'.$v['pageTitle'].'</a>'.
            (isset($v['itemTitle']) ? ' → <a href="'.$v['pagePath'].'/'.$v['itemId'].'" target="_blank">'.$v['itemTitle'].'</a>' : '') ?>
          </li>
        <? } ?>
      </ul>
      </div>
      <script type="text/javascript">
      new Ngn.NotifyTypeEdit($('btn_<?= $k ?>'), $('si_<?= $k ?>'));
      </script>
    <? } ?>
  <? } ?>
  
  <p class="info">Новыми сообщениями будут считаться только те, что были добавлены 
  после нажатия вами на конпку «Сохранить»</p>
  <p><input type="submit" value="Сохранить" /></p>
  </form>
<? } else { ?>
  <? Tt::tpl('common/authorize') ?>
<? } ?>