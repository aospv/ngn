    <div id="pathNav">
      <div class="btns">
        <div class="smIcons bordered last">
          <? if (isset($d['allowedActions']) and in_array('edit', $d['allowedActions'])) { ?>
            <? if ($d['page']['controller'] == 'profile') { ?>
              <a href="<?= Tt::getPath(1).'/'.$d['item']['id'] ?>?a=edit" class="sm-edit" title="Редактировать"><i></i>Редактировать <?= $d['page']['title'] ?></a>
            <? } elseif ($d['item']) {
              if (in_array('activate', $d['allowedActions'])) {
                if ($d['settings']['premoder']) {
                  Tt::tpl('editBlocks/premoderBlock', $d['item']);
                } else
                  Tt::tpl('editBlocks/editBlock', $d['item']);
              } else {
                Tt::tpl('editBlocks/editOnlyBlock', $d['item']);
              }
            }
          } ?>
        </div>
        <div>
          <div class="smIcons bordered">
            <? if ($d['isRss']) { ?>
              <a href="<?= Tt::getPath() ?>?a=rss" class="sm-rss tooltip" title="RSS «<?= $d['rssTitle'] ? $d['rssTitle'] : $d['page']['title'] ?>»"><i></i></a>
            <? } ?>
            <? if (Config::getVarVar('dd', 'enableSubscribe', true) and $d['isItemsController'] and !$d['page']['mysite']) { ?>
            <? if ($d['action'] == 'showItem') { ?>
              <? if ($d['subscribedNewComments']) { ?>
                <a href="#" class="sm-subscribed" id="btnSubscribe"><i></i></a>
              <? } else { ?>
                <a href="#" class="sm-unsubscribed" id="btnSubscribe"><i></i></a>
              <? } ?>
              <script type="text/javascript">
              new Ngn.SubscribeLink('btnSubscribe', 'NewComments', {
                titleOn: "Подписаться на новые комментарии записи «<?= $d['item']['title'] ?>»",
                titleOff: "Отписаться от новых комментариев записи «<?= $d['item']['title'] ?>»",
                authorized: <?= Auth::get('id') ? 'true' : 'false' ?>
              });
              </script>
            <? } elseif ($d['action'] == 'list')  { ?>
              <? if ($d['subscribedNewItems']) { ?>
                <a href="#" class="sm-subscribed" id="btnSubscribe"><i></i></a>
              <? } else { ?>
                <a href="#" class="sm-unsubscribed" id="btnSubscribe"><i></i></a>
              <? } ?>
              <script type="text/javascript">
              new Ngn.SubscribeLink('btnSubscribe', 'NewItems', {
                titleOn: "Подписаться на новые записи раздела «<?= $d['page']['title'] ?>»",
                titleOff: "Отписаться от новых записей раздела «<?= $d['page']['title'] ?>»",
                authorized: <?= Auth::get('id') ? 'true' : 'false' ?>
              });
              </script>
            <? } ?>
            <? } ?>
          </div>
        </div>
      </div>
      <? Tt::tpl('common/path', $d['pathData']) ?>
    </div>
