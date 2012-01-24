<?

$c = Html::getInnerContent(
  iconv('windows-1251', CHARSET, file_get_contents('http://padonki.org/archieve/diagnosis/2011/0.do')),
  '<table border="1" style="border-collapse: collapse; border: 1px solid black;">',
  '<!--%%content%%-->'
);

function parse_links($c) {
  preg_match_all('/<a.*href=["\'](.*)["\'][^>]*>(.*)<\/a>/siU', $c, $m);
  return $m;
}

$m = parse_links($c);
foreach ($m[1] as $v) {
  if (!strstr($v, 'creo')) continue;
  $page = iconv('windows-1251', CHARSET, file_get_contents('http://padonki.org'.$v));
  $c = Html::getInnerContent(
    $page,
    '<!-- дальше идет текст статьи--->',
    '<!--тут заканчивается текст статьи-->'
  );
  $a = Html::getInnerContent($page,
    '<td width="45%" align="left">',
    '<!--br>[&nbsp;<b>Креативы&nbsp;автора</b>&nbsp;]--></td>'
  );
  $m2 = parse_links($a);
  //die2($m2);
  $comments = Html::getInnerContent(
    $page,
    '<!-- comments -->',
    '<!-- comment input -->'
  );
  preg_match_all('/<div class="left_cc_item">(.*)<\/div>/msU', $comments, $m2);
  preg_match('/<b>(.*)<\/b>.*<div class="comment_message">(.*)<\/div>/ms', $m2[0][0], $m3);
  die2($m3);
/*  
  <div class="comment_message">первыйнах
<br>вроде этот креатив раньше где то был..
<br>или ошибаюсь?</div>
<div class="time"><span>11.12.11 00:34</span>

</div>
</div>
  */
}
