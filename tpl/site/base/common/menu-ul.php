<?

if (empty($d)) return;
print '<ul>';
foreach ($d as $v)
  print '<li'.($v['selected'] ? ' class="selected"' : '').'>'.
    '<a href="'.$v['link'].'"><span>'.
    $v['title'].
    '</span></a><i></i><div class="clear"></div>'.
    (!empty($v['children']) ? Tt::getTpl('common/menu-ul', $v['children']) : '').
    '</li>'; 
print '<div class="clear"><!-- --></div>';
print '</ul>';
