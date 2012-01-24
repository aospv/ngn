<?php

/*

  $d => array(
    [pagePath] => galereja
    [id] => 4
    [type] => commentsCount
    [title] => Количество комментариев
    [name] => commentsCount
    [authorId] => 1
    [authorLogin] => admin
    [v] => 
    [o] => Ddo Object
  )

*/

if ($d['v']) {
  print '<div class="smIcons" style="float:right">';
  print '<a class="sm-comments'.($d['v'] > 2 ? '2' : '').' shortComments"';
  print 'title="комментарии ('.$d['v'].')"'; 
  print 'href="'.$d['pagePath'].'/'.$d['id'].'#msgs"><i></i>';
  print $d['v'];
  print '</a><div class="clear"><!-- --></div>';
  print '</div>';
}