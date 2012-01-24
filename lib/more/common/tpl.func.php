<?php

/**
 * Приводит текст вида "http://site1.com, http://site2.com, ..." в 
 * список тэгов:
 * <a href="http://site1.com" target="_blank">http://site1.com</a>,
 * <a href="http://site2.com" target="_blank">http://site2.com</a>,
 * <a href="..." target="_blank">...</a>  
 *
 * @param   string  Ссылки через запятую
 * @return  string  HTML
 */
function urls($t, $delimiter = ',', $tpl = '<li><a href="$1" target="_blank">$1</a></li>') {
  if (trim($t) == '') return '';
  return preg_replace('/([^'.$delimiter.']*)'.$delimiter.'/u', $tpl.$delimiter, 
    $t.$delimiter);
}


function clearUrl($url) {
  $url = preg_replace('/^http:\/\/(.*)$/', '$1', $url);
  if (preg_match('/^([^\/]*)\/*$/', $url))
    return preg_replace('/^([^\/]*)\/*$/', '$1', $url);
  return $url;
}

function cnt(&$arr) {
  $cnt = count($arr);
  return $cnt ? '('.$cnt.')' : '';
}

function getLinks($links, $selectedName) {
  foreach ($links as &$link)
    if (isset($link['name']) and $link['name'] == $selectedName)
      $link['selected'] = true;
  return $links;
}

function ul($options) {
  $s = "<ul>\n";
  foreach ($options as $option) {
    $s .= '<li><a href="'.$option['link'].'"'.(!empty($option['selected']) ? ' class="selected"' : '').'">'.$option['title']."</a></li>\n";
  }
  $s .= "</ul>\n";
  return $s;
}

function ol($items) {
  $s = '<ol>';
  foreach ($items as $item) $s .= '<li>'.$item.'</li>';
  return $s.'</ol>';
}

function externalLinkTag($link) {
  return '<a href="'.$link.'">'.$link.'</a>';
}
