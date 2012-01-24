<?

/* @var $oPM PrivMsgs */
$oPM = O::get('PrivMsgs', Auth::get('id'));

if (($msgs = $oPM->getNewMsgs())) {
  $n=0;
  foreach ($msgs as $k => $v)
    if ($v['userId'] == NOTIFY_ROBOT_ID)
      $msgs[$k]['system'] = true;
  $msgs = Arr::sort_by_order_key($msgs, 'system');
  foreach ($msgs as $v) {
    $n++;  if ($n == 50) break;
    if (strstr($v['text'], '<span class="commentText">')) {
      $text = preg_replace_callback(
        '/<span class="commentText">(.*)<\/span>/mUs',
        create_function(
          '$m',
          'return Misc::cut($m[1], 200);'
        ),
        $v['text']);
    } else {
      $text = 
        '<div class="avatar'.(!empty($v['sm_image']) ? '' : ' noAvatar').'">'.
        '<img src="/'.(!empty($v['sm_image']) ? $v['sm_image'] : './m/img/no-avatar.gif').'" '.
        'title="'.$v['login'].'"></div>'.
        '<p><b>'.$v['login'].'</b>:</p>'.
        '<p>'.Misc::cut($v['text'], 200).'</p><p><a href="'.Tt::getControllerPath('privMsgs').'">прочитать сообщение →</a></p>';
    }
    $items[] = '<div class="pmw'.($v['system'] ? ' pmSystem' : '').'" id="pmw'.$v['id'].'">'.$text.'</div>';
  }
  Tt::tpl('slideTips/common', array(
    'class' => 'topSlideTipPm',
    'items' => $items
  ));
}

