<?php return;?>
<div id="devPanel" class="adminPanel">
  <div class="adminPanelDrag"></div>
  <div class="adminPanelBody">
<?php

$domain = SITE_DOMAIN;

$types = array('dev', 'test', 'prod');

preg_match('/([^.]+)\.(.*)/', $domain, $m);
if ($m[1] == 'dev') $type = 'dev';
elseif ($m[1] == 'test') $type = 'test';
else $type = 'prod';

if ($m[1] == 'dev' or $m[1] == 'test') $base = $m[2];
else $base = $domain;

print '<table cellpadding="0" cellspacing="0"><tr><td>';
foreach ($types as $t) {
  print ($type != $t ? '&bull; <a href="http://'.
                       ($t == 'prod' ? '' : $t.'.').
                       $base.$_SERVER['REQUEST_URI'].'">'.$t.'</a>' : 
                       '&bull; <b>'.$t.'</b>').'<br />';
}
print '</td><td>';

print "&bull; ".
      ($d['page']['module'] ? 'module: <i>'.$d['page']['module'].'</i>, ' : '').
      ($d['page']['controller'] ? 'controller: <i>'.$d['page']['controller'].'</i>, ' : '').
      "action: <i>{$d['action']}</i><br />";
print '&bull; Время генерации: '.getProcessTime().' сек.<br />';
print '&bull; Память: '.memory_get_usage().'<br />';

print '&bull; <a href="'.Tt::getPath(0).'/admin">Панель управления</a><br />';
if (DATA_CACHE === true) print '&bull; Кэш данных '.CACHE_METHOD.' включен. <a href="./c/panel/cc">очистить</a>';
else print '&bull; Кэш данных '.CACHE_METHOD.' выключен';
print '<br />';

if (DEBUG_STATIC_FILES === true)
  print '&bull; Отладка статических файлов<br />';

print '&bull; Запросов к БД: <a href="" id="sqlDataOpen">'.R::get('sqlN').'</a><br />';

if ($d['action'] == 'new') {
  print '&bull; <a href="'.$_SERVER['REQUEST_URI'].'&editNotAllowed=1">Переключить в режим ограниченных прав</a>';
}

print '</td></tr></table>';

?>

    <style>
    #sqlData {
    padding-top: 15px;
    }
    #sqlData .backtrace {
    color: #555555;
    display: none;
    }
    #sqlData pre {
    margin: 0px;
    }
    #sqlData hr {
    margin: 0px;
    }
    #sqlDataToggleBacktrace {
    display: block;
    margin-bottom: 10px;
    }
    #sqlData .info {
    color: #007F05;
    }
    </style>
    <div id="sqlData" style="display:none">
      <a href="" id="sqlDataToggleBacktrace">Переключить backtrace'ы</a>
      <? foreach (R::get('sqlData') as $v) { ?>
         <pre><?= $v['sql'] ?></pre>
         <div class="backtrace">
           <div class="info"><?= $v['info'] ?></div>
           <?= nl2br($v['backtrace']) ?>
         </div>
         <hr />
      <? } ?>
    </div>
  </div>
</div>

<script type="text/javascript">
new Ngn.DevPanel($('devPanel'));
</script>